<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

use App\Models\DataJob;
use App\Models\Setting;
use App\Models\DataPasangBaru;
use App\Models\Karyawan;
use App\Models\OnlineApi;
use App\Models\TeknisiCadangan;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class DataJobController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:data-job-list', ['only' => ['index','detail']]);
        $this->middleware('permission:data-job-create', ['only' => ['create','store']]);
        $this->middleware('permission:data-job-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:data-job-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $title = 'Data Job';
        $appName = Setting::first();
        $listPasangBaru = DataPasangBaru::where('status','0')
        ->whereDoesntHave('data_job')
        ->orderBy('created_at','ASC')
        ->get();

        $toDay = Carbon::now()->format('Y-m-d');
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

        $listKaryawan = Karyawan::with('absensi')
        ->whereHas('absensi', function($e) use ($toDay){
            $e->whereDate('created_at',$toDay);
        }) // => karyawan yang sudah absensi
        ->whereDoesntHave('dataJob', function($e) use ($toDay){
            $e->whereDate('created_at',$toDay);
        })
        ->orWhereHas('teknisiCadangan', function($e) use ($toDay){
            $e->whereDate('created_at',$toDay);
        })
        ->where('is_verifikasi',1)
        ->get();

        return view('dashboard.data_job.index', compact('title','appName','listPasangBaru','teknisiCadangan','teknisiNonJob','listKaryawan'));
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
					$btn = '<a href="data-job/'.$row->id.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    if($row->dataPasangBaru->status != 3){
                        $btn = $btn.' <a href="data-job/edit/'.$row->idjob.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                        $btn = $btn.' <button type="button" href="data-job/hapus/'.$row->idjob.'" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    }
                    return $btn;
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

    public function store(Request $request)
	{
        $client = New Client();
        $onlineApi = OnlineApi::first();

        try {
            $validator = Validator::make($request->all(),[
                'user_id' => 'required',
                'kode_pasang_baru' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'user_id' => $request->user_id,
                'kode_pasang_baru' => $request->kode_pasang_baru,
            ];

            DB::transaction(function () use ($request, $data, $client, $onlineApi){
                $dataJob = DataJob::create($data);

                $client->request('POST', $onlineApi->website . '/api/data-job/' . $dataJob->user->idapi . '/' . $dataJob->dataPasangBaru->pasang_baru_api);
                
                $teknisiCadangan = TeknisiCadangan::where('user_id', $dataJob->user_id)
                ->first();
                
                if ($teknisiCadangan) {
                    $teknisiCadangan->delete();
                    
                    $client->request('DELETE', $onlineApi->website . '/api/teknisi-cadangan/' . $teknisiCadangan->user->idapi . '/delete');
                }
            });

            Alert::success('Sukses','Data Job Baru berhasil disimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

		return redirect()->back();
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

    public function edit($id)
    {
        $title = 'Edit Data Job';
        $appName = Setting::first();
        
        $dataJob = DataJob::findOrFail($id);

        $hariIni = Carbon::now()->format('Y-m-d');
        $listAbsensi = Absensi::with('user')
        ->absensiBerlaku()
        ->whereDate('created_at', $hariIni)
        ->orWhereHas('user', function($query){
            $query->whereDoesntHave('dataJob');
            $query->orWhereHas('teknisiCadangan');
        })
        ->get();
        
        return view('dashboard.data_job.edit', compact('title','appName','dataJob','listAbsensi'));
    }

    public function update(Request $request, $id)
	{
        $client = New Client();
        $onlineApi = OnlineApi::first();

        try {
            $dataJob = DataJob::findOrFail($id);

            $validator = Validator::make($request->all(),[
                'user_id' => ['required'],
                'kode_pasang_baru' => ['required'],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'user_id' => $request->user_id,
                'kode_pasang_baru' => $request->kode_pasang_baru,
            ];

            DB::transaction(function () use ($dataJob, $data, $client, $onlineApi) {
                $dataJob->update($data);

                $client->request('PUT', $onlineApi->website . '/api/data-job/' . $dataJob->user->idapi . '/' . $dataJob->dataPasangBaru->pasang_baru_api);
            });

            Alert::success('Sukses','Data Job Baru berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

		return redirect()->back();
	}
    
    public function delete($id){
        try {
            DataJob::where('id',$id)->delete();

            Alert::success('Sukses','Data Job berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        return redirect()->back();
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
