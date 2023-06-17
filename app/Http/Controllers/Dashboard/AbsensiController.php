<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Absensi;
use App\Models\DataJob;
use App\Models\DataPasangBaru;
use App\Models\Setting;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

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

        $waktuAbsensi = $appName;
        $awalAbsensi = $waktuAbsensi->awal_absensi;
        $akhirAbsensi = $waktuAbsensi->akhir_absensi;
        $jamSekarang = Carbon::now()
        ->format('H:i:s');

        return view('dashboard.absensi.index', compact('title','appName','listKaryawan','awalAbsensi','akhirAbsensi','jamSekarang'));
    }

    public function add_absensi(){
        try {
            $karyawan = Auth::user()->id;
            $dataPasangBaru = DataPasangBaru::whereDoesntHave('data_job')
            ->first();

            $createAbsensi = [
                'user_id' => $karyawan,
                'waktu_absen' => date('Y-m-d'),
            ];

            DB::transaction(function() use ($karyawan, $dataPasangBaru, $createAbsensi){
                $absensi = Absensi::create($createAbsensi);

                if ($dataPasangBaru) {
                    DataJob::create([
                        'user_id' => $absensi->user_id,
                        'kode_pasang_baru' => $dataPasangBaru->id,
                    ]);
                }
            });

            Alert::success('Sukses','Terima kasih, sudah mengisi absensi hari ini');
        } catch (Exception $e) {
            DB::rollback();

            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }

    public function store(Request $request)
	{
        $pasangBaru = DataPasangBaru::whereDoesntHave('data_job')
        ->first();

        try {

            $validator = Validator::make($request->all(),[
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'user_id' => $request->input('user_id'),
                'waktu_absen' => date('Y-m-d'),
            ];

            DB::transaction(function() use ($pasangBaru, $data){
                $absensi = Absensi::create($data);

                if ($pasangBaru) {
                    DataJob::create([
                        'user_id' => $absensi->user_id,
                        'kode_pasang_baru' => $pasangBaru->id,
                    ]);
                }
            });

            Alert::success('Sukses','Data Absensi berhasil disimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

		return redirect()->back();
	}

    /**
     * @throws Exception
     */
    public function getJsonAbsensi(Request $request)
    {
        if ($request->ajax()) {
			$data = Absensi::query();
            $data->join('users','absensis.user_id','=','users.id')
			->select([
                'absensis.id as id_absensi',
                'absensis.waktu_absen',
                'absensis.status',
                'absensis.created_at as create_absensi',
                'users.name as namakaryawan'
            ])
            ->orderBy('absensis.created_at','DESC');

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if ($request->get('waktu_absen') != null) {
                        $instance->where('absensis.waktu_absen', $request->input('waktu_absen'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->create_absensi ? with(new Carbon($row->create_absensi))->isoFormat('LLLL') : '';
                })

                ->addColumn('status', function ($row) {
                    $badge = $row->status == 1 ? 'badge-success' : ($row->status == 2 ? 'badge-warning' : '');
                    $status = $row->status == 1 ? 'Sudah Absensi' : ($row->status == 2 ? 'Berhalangan' : '');

                    return '<span class="badge '.$badge.'">'.$status.'</span>';
                })

                ->addColumn('action', function($row){
                    $hariIni = Carbon::now()->format('Y-m-d');
                    $tanggalAbsensi = Carbon::parse($row->waktu_absen)->format('Y-m-d');

                    if ($tanggalAbsensi == $hariIni){
                        return '<a href="absensi/edit/'.$row->id_absensi.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                    }else{
                        return null;
                    }
                })

                ->rawColumns(['status','action'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function delete($id)
    {
        try {
            $absensi = Absensi::findOrFail($id);
            $absensi->delete();

            Alert::success('Sukses','Data Absensi berhasil dihapus');
        } catch (Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        return redirect()->back();
    }

    public function edit($id)
    {
        $title = 'Edit Absensi Karyawan';
        $appName = Setting::first();
        $data = Absensi::with('user')
            ->findOrFail($id);
        $cekJob = DataJob::where('user_id',$data->user_id)
        ->count();

        return view('dashboard.absensi.edit', compact('title','appName','data','cekJob'));
    }

    public function update(Request $request, $id)
	{
        try {
            $validator = Validator::make($request->all(),[
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'status' => $request->input('status')
            ];

            Absensi::where('id',$id)
                ->update($data);

            Alert::success('Sukses','Data Absensi berhasil diupdate');
        } catch (\Throwable $e) {
            Alert::error('Error',$e->getMessage());
        }

		return redirect()->route('absensi.index');
	}
}
