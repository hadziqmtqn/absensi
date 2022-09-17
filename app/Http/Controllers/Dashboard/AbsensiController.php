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

        $waktuAbsensi = Setting::select('awal_absensi','akhir_absensi')->first();
        $awalAbsensi = $waktuAbsensi->awal_absensi;
        $akhirAbsensi = $waktuAbsensi->akhir_absensi;
        $jamSekarang = Carbon::now()->format('H:i:s');
        
        return view('dashboard.absensi.index', compact('title','appName','listKaryawan','awalAbsensi','akhirAbsensi','jamSekarang'));
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
                    if($row->status == 1){
                        return '<span class="badge badge-success">Sudah Absensi</span>';
                    }elseif($row->status == 2){
                        return '<span class="badge badge-warning">Berhalangan</span>';
                    }
                })

                ->addColumn('action', function($row){
                    if($row->created_at->format('Y-m-d') == Carbon::now()->format('Y-m-d')){
                        $btn = '<a href="absensi/edit/'.$row->id.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                        return $btn;
                    }
                })

                ->rawColumns(['status','action'])
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

    public function edit($id)
    {
        $title = 'Edit Absensi Karyawan';
        $appName = Setting::first();
        $data = Absensi::with('user')->findOrFail($id);
        $cekJob = DataJob::where('user_id',$data->user_id)
        ->count();
        
        return view('dashboard.absensi.edit', compact('title','appName','data','cekJob'));
    }

    public function update(Request $request, $id)
	{
        try {
            $request->validate([
                'status' => 'required',
            ]);
    
            $data['status'] = $request->status;
            // $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            Absensi::where('id',$id)->update($data);

            Alert::success('Sukses','Data Absensi berhasil diupdate');
        } catch (\Throwable $e) {
            Alert::error('Error',$e->getMessage());
        }

		return redirect()->route('absensi.index');
	}
}
