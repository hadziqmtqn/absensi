<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

use App\Models\DataJob;
use App\Models\Setting;
use App\Models\DataPasangBaru;
use App\Models\Karyawan;
use App\Models\TeknisiCadangan;
use Carbon\Carbon;

class DataJobController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:data-job-list', ['only' => ['index']]);
    }

    public function index()
    {
        $title = 'Data Job';
        $appName = Setting::first();
        $listPasangBaru = DataPasangBaru::where('status','0')
        ->whereDoesntHave('data_job')
        ->orderBy('created_at','ASC')
        ->get();
        $teknisiCadangan = TeknisiCadangan::whereDate('created_at', Carbon::now())->count();
        $teknisiNonJob = Karyawan::whereHas('absensi', function($e){
            $e->where('status','1');
            $e->whereDate('created_at', Carbon::now());
        })
        ->whereDoesntHave('dataJob', function($e){
            $e->whereDate('created_at', Carbon::now());
        })
        ->count();

        $listKaryawan = Karyawan::with('absensi')
        ->whereHas('absensi', function($e){
            $hariIni = Carbon::now()->format('Y-m-d');
            $e->whereDate('created_at',$hariIni);
        }) // => karyawan yang sudah absensi
        ->whereDoesntHave('dataJob', function($e){
            $hariIni = Carbon::now()->format('Y-m-d');
            $e->whereDate('created_at',$hariIni);
        })
        ->orWhereHas('teknisiCadangan', function($e){
            $hariIni = Carbon::now()->format('Y-m-d');
            $e->whereDate('created_at',$hariIni);
        })
        ->where('is_verifikasi',1)
        ->get();

        return view('dashboard.data_job.index', compact('title','appName','listPasangBaru','teknisiCadangan','teknisiNonJob','listKaryawan'));
    }

    public function getJsonDataJob(Request $request)
    {
        if ($request->ajax()) {
            $data = DataJob::select('data_jobs.id as idjob','data_jobs.user_id','data_jobs.created_at','users.name as karyawan',
            'data_pasang_barus.kode','data_pasang_barus.inet','data_pasang_barus.nama_pelanggan','data_pasang_barus.no_hp','data_pasang_barus.alamat',
            'data_pasang_barus.status')
            ->join('users','data_jobs.user_id','users.id')
            ->join('data_pasang_barus','data_jobs.kode_pasang_baru','=','data_pasang_barus.id')
            ->orderBy('data_jobs.created_at','DESC');

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('status') == '0' || $request->get('status') == '1' || $request->get('status') == '2' || $request->get('status') == '3') {
                        $instance->where('status', $request->get('status'));
                    }

                    if ($request->get('created_at') != null) {
                        $instance->whereDate('data_jobs.created_at', $request->created_at);
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('data_pasang_barus.kode', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.nama_pelanggan', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.no_hp', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.alamat', 'LIKE', "%$search%")
							->orWhere('data_jobs.created_at', 'LIKE', "%$search%")
                            ->orWhere('users.name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('lll') : '';
                })

                ->addColumn('action', function($row){
					$btn = '<a href="data-job/'.$row->idjob.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    if($row->status < 3){
                        $btn = $btn.' <a href="data-job/edit/'.$row->idjob.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                    }else{
                        $btn = $btn.' <button type="button" class="btn btn-warning disabled" style="padding: 7px 10px">Edit</button>';
                    }
                    $btn = $btn.' <button type="button" href="data-job/hapus/'.$row->idjob.'" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    return $btn;
                })

                ->addColumn('status', function($row){
                    if($row->status == 0){
                        return '<span class="badge badge-info">Waiting</span>';
                    }elseif($row->status == 1){
                        return '<span class="badge badge-primary">In Progress</span>';
                    }elseif($row->status == 2){
                        return '<span class="badge badge-warning">Pending</span>';
                    }elseif($row->status == 3){
                        return '<span class="badge badge-success">Success</span>';
                    }
                })

                ->rawColumns(['action','status'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function detail($id)
    {
        $title = 'Detail Data Job';
        $appName = Setting::first();
        $data = DataJob::find($id);
        $listDataJob = DataJob::orderBy('created_at','DESC')->get();

        if($data->dataPasangBaru->status == 0){
            $badge = 'badge-info';
            $status = 'Waiting';
        }elseif($data->dataPasangBaru->status == 1){
            $badge = 'badge-primary';
            $status = 'In Progress';
        }elseif($data->dataPasangBaru->status == 2){
            $badge = 'badge-warning';
            $status = 'Pending';
        }elseif($data->dataPasangBaru->status == 3){
            $badge = 'badge-success';
            $status = 'Success';
        }

        return view('dashboard.data_job.detail', compact('title','appName','data','listDataJob','badge','status'));
    }

    public function teknisiNonJob()
    {
        $title = 'Teknisi Non Job';
        $appName = Setting::first();

        return view('dashboard.data_job.teknisi-non-job', compact('title','appName'));
    }

    public function getJsonTeknisiNonJob(Request $request)
    {
        if ($request->ajax()) {
            $data = Karyawan::select('users.name','absensis.created_at')
            ->join('absensis','users.id','=','absensis.user_id')
            ->whereHas('absensi', function($e){
                $e->where('status', '1');
                $e->whereDate('created_at', Carbon::now());
            })
            ->whereDoesntHave('dataJob', function($e){
                $e->whereDate('created_at', Carbon::now());
            });

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('lll') : '';
                })

                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }
}
