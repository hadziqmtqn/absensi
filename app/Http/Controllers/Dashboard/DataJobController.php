<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Models\DataJob;
use App\Models\Setting;
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

        $toDay = Carbon::now();
        $teknisiCadangan = TeknisiCadangan::whereDate('created_at', $toDay)
        ->count();
        $teknisiNonJob = Karyawan::whereHas('absensi', function($query) use ($toDay){
            $query->where('status','1');
            $query->whereDate('created_at', $toDay);
        })
        ->whereDoesntHave('dataJob', function($query) use ($toDay){
            $query->whereDate('created_at', $toDay);
        })
        ->count();

        return view('dashboard.data_job.index', compact('title','appName','teknisiCadangan','teknisiNonJob'));
    }

    public function getJsonDataJob(Request $request)
    {
        if ($request->ajax()) {
            $data = DataJob::with('user','dataPasangBaru')
            ->orderBy('data_jobs.created_at','DESC');

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('status') != null) {
                        $instance->whereHas('dataPasangBaru', function($query) use ($request){
                            $query->where('status', $request->get('status'));
                        });
                    }

                    if ($request->get('created_at') != null) {
                        $instance->whereDate('data_jobs.created_at', $request->created_at);
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->whereHas('dataPasangBaru', function($query) use ($search){
                                $query->where('kode', 'LIKE', "%$search%");
                                $query->orWhere('nama_pelanggan', 'LIKE', "%$search%");
                                $query->orWhere('alamat', 'LIKE', "%$search%");
                            })
                            ->orWhereHas('user', function($query) use ($search){
                                $query->where('name', 'LIKE', "%$search%");
                            });
                        });
                    }
                })

                ->addColumn('kode', function($row){
                    return $row->dataPasangBaru->kode;
                })
                
                ->addColumn('user', function($row){
                    return $row->user->name;
                })

                ->addColumn('nama_pelanggan', function($row){
                    return $row->dataPasangBaru->nama_pelanggan;
                })
                
                ->addColumn('no_hp', function($row){
                    return !$row->dataPasangBaru ? null : $row->dataPasangBaru->no_hp;
                })
                
                ->addColumn('alamat', function($row){
                    return !$row->dataPasangBaru ? null : $row->dataPasangBaru->alamat;
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('lll') : '';
                })

                ->addColumn('action', function($row){
					return '<a href="data-job/'.$row->id.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                })

                ->addColumn('status', function($row){
                    $badge = $row->dataPasangBaru->status == '0' ? 'badge-info' : ($row->dataPasangBaru->status == '1' ? 'badge-primary' : ($row->dataPasangBaru->status == '2' ? 'badge-warning' : 'badge-success'));
                    $status = $row->dataPasangBaru->status == '0' ? 'Waiting' : ($row->dataPasangBaru->status == '1' ? 'In Progress' : ($row->dataPasangBaru->status == '2' ? 'Pending' : 'Success'));

                    return '<span class="badge '.$badge.'">'.$status.'</span>';
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
