<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

use App\Models\Absensi;
use App\Models\DataJob;
use App\Models\DataPasangBaru;
use App\Models\Setting;
use App\Models\Karyawan;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:absensi-list|absensi-create|absensi-edit|absensi-delete', ['only' => ['index','show']]);
        $this->middleware('permission:absensi-create', ['only' => ['create','store']]);
        $this->middleware('permission:absensi-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:absensi-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        if (Auth::user()->role_id == 1) {
            $title = 'Absensi Karyawan';
            $appName = Setting::first();
            $listKaryawan = Karyawan::where('role_id',2)->select('id','name')
            ->withCount('absensi')
            ->whereDoesnthave('absensi', function($e){
                $hariIni = Carbon::now()->format('Y-m-d');
                $e->where('waktu_absen', $hariIni);
            })
            ->where('is_verifikasi',1)
            ->orderBy('name','ASC')
            ->get();

            return view('dashboard.absensi.index', compact('title','appName','listKaryawan'));
        } else {
            $title = 'Absensi Karyawan';
            $appName = Setting::first();
            $waktuAbsensi = Setting::select('awal_absensi','akhir_absensi')->first();
            $awalAbsensi = $waktuAbsensi->awal_absensi;
            $akhirAbsensi = $waktuAbsensi->akhir_absensi;
            $jamSekarang = Carbon::now()->format('H:i:s');
            $hariIni = Carbon::now()->format('Y-m-d');
            
            $cekAbsensi = Absensi::where('user_id',Auth::user()->id)->where('waktu_absen',$hariIni)->whereBetween(DB::raw('TIME(created_at)'), array($awalAbsensi, $akhirAbsensi))->count();
            
            return view('dashboard.absensi.karyawan', compact('title','appName','cekAbsensi','awalAbsensi','akhirAbsensi','jamSekarang','hariIni'));
        }
    }
    
    public function add_absensi(){
        try {
            $karyawan = Auth::user()->id;
            $cekPasangBaru = DataPasangBaru::whereDoesntHave('data_job')->count();

            $absensi['user_id'] = $karyawan;
            $absensi['waktu_absen'] = date('Y-m-d');
            $absensi['created_at'] = date('Y-m-d H:i:s');
            $absensi['updated_at'] = date('Y-m-d H:i:s');

            if($cekPasangBaru < 1){
                Absensi::insert($absensi);
            }else{
                $pasangBaru = DataPasangBaru::select('id')->whereDoesntHave('data_job')->first();
        
                DB::beginTransaction();
                
                Absensi::insert($absensi);
                
                $dataJob['user_id'] = $karyawan;
                $dataJob['kode_pasang_baru'] = $pasangBaru->id;
                $dataJob['created_at'] = date('Y-m-d H:i:s');
                $dataJob['updated_at'] = date('Y-m-d H:i:s');
    
                DataJob::insert($dataJob);
    
                DB::commit();    
            }

            Alert::success('Sukses','Terima kasih, sudah mengisi absensi hari ini');
        } catch (\Exception $e) {
            DB::rollback();

            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }

    public function store(Request $request)
	{
        try {
            $cekPasangBaru = DataPasangBaru::whereDoesntHave('data_job')->count();
    
            $request->validate([
                'user_id' => 'required',
            ]);
    
            $data['user_id'] = $request->user_id;
            $data['waktu_absen'] = date('Y-m-d');
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            if($cekPasangBaru < 1){
                Absensi::insert($data);
            }else{
                $pasangBaru = DataPasangBaru::select('id')->whereDoesntHave('data_job')->first();
        
                DB::beginTransaction();
                
                Absensi::insert($data);
                
                $dataJob['user_id'] = $request->user_id;
                $dataJob['kode_pasang_baru'] = $pasangBaru->id;
                $dataJob['created_at'] = date('Y-m-d H:i:s');
                $dataJob['updated_at'] = date('Y-m-d H:i:s');
    
                DataJob::insert($dataJob);
    
                DB::commit(); 
            }

            Alert::success('Sukses','Data Absensi berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollback();

            Alert::error('Error',$e->getMessage());
        }

		return redirect()->back();
	}

    public function getJsonAbsensi(Request $request)
    {
        if ($request->ajax()) {
			$data = Absensi::select('absensis.*','users.name as namakaryawan')
			->join('users','absensis.user_id','=','users.id')
            ->orderBy('created_at','DESC');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if ($request->get('waktu_absen') != null) {
                        $instance->where('waktu_absen', $request->waktu_absen);
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('LLLL') : '';
                })

                ->addColumn('status', function ($row) {
                    if($row->waktu_absen){
                        return '<span class="badge badge-success">Sudah Absensi</span>';
                    }else{
                        return '<span class="badge badge-warning">Belum Absensi</span>';
                    }
                })

                ->rawColumns(['status'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function delete($id){
        try {
            Absensi::where('id',$id)->delete();

            Alert::success('Sukses','Data Absensi berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        return redirect()->back();
    }
}
