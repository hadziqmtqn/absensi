<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use App\Models\DataJob;
use App\Models\Setting;
use App\Models\DataPasangBaru;
use App\Models\Karyawan;
use App\Models\TeknisiCadangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

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
            $query->absensiBerlaku();
            $query->whereDate('created_at', $toDay);
        })
        ->whereDoesntHave('dataJob', function($query) use ($toDay){
            $query->whereDate('created_at', $toDay);
        })
        ->count();

        $listKaryawan = Karyawan::with('absensi')
        ->whereHas('absensi', function($query) use ($toDay){
            $query->absensiBerlaku();
            $query->whereDate('created_at',$toDay);
        }) // => karyawan yang sudah absensi
        ->whereDoesntHave('dataJob', function($query) use ($toDay){
            $query->whereDate('created_at',$toDay);
        })
        ->orWhereHas('teknisiCadangan', function($query) use ($toDay){
            $query->whereDate('created_at',$toDay);
        })
        ->where('is_verifikasi',1)
        ->get();

        return view('dashboard.data_job.index', compact('title','appName','listPasangBaru','teknisiCadangan','teknisiNonJob','listKaryawan'));
    }

    public function getJsonDataJob(Request $request)
    {
        if ($request->ajax()) {
            $data = DataJob::query()
                ->with('user','dataPasangBaru')
                ->orderBy('created_at','DESC');

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->filled('status') != null) {
                        $instance->whereHas('dataPasangBaru', function($query) use ($request){
                            $query->where('status', $request->input('status'));
                        });
                    }

                    if ($request->filled('created_at') != null) {
                        $instance->whereDate('data_jobs.created_at', $request->input('created_at'));
                    }

                    if (!empty($request->filled('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->input('search');
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
					$btn = '<a href="/data-pasang-baru/'.$row->dataPasangBaru->id.'/detail" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    if($row->dataPasangBaru->status != 3){
                        $btn = $btn.' <a href="data-job/edit/'.$row->id.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                        $btn = $btn.' <button type="button" href="data-job/hapus/'.$row->id.'" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
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
        try {
            $validator = Validator::make($request->all(),[
                'user_id' => ['required'],
                'kode_pasang_baru' => ['required'],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'user_id' => $request->input('user_id'),
                'kode_pasang_baru' => $request->input('kode_pasang_baru'),
            ];

            DB::transaction(function () use ($data){
                $dataJob = DataJob::create($data);

                $teknisiCadangan = TeknisiCadangan::where('user_id', $dataJob->user_id)
                ->first();

                if (!is_null($teknisiCadangan)) {
                    $teknisiCadangan->delete();
                }
            });

            Alert::success('Sukses','Data Job Baru berhasil disimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

		return redirect()->back();
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

            $dataJob->update($data);

            Alert::success('Sukses','Data Job Baru berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

		return redirect()->back();
	}

    public function delete($id)
    {
        $dataJob = DataJob::findOrFail($id);

        $hariIni = date('Y-m-d');
        $user = User::withCount('dataJob')
        ->whereHas('dataJob', function($query) use ($hariIni, $dataJob){
            $query->where('user_id', $dataJob->user_id);
            $query->whereDate('created_at', $hariIni);
        })
        ->first();

        try {
            DB::transaction(function() use ($dataJob, $user){
                if ($user->data_job_count > 1) {
                    TeknisiCadangan::create([
                        'user_id' => $dataJob->user_id
                    ]);
                }

                $dataJob->delete();
            });

            Alert::success('Sukses','Data Job berhasil dihapus');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
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
