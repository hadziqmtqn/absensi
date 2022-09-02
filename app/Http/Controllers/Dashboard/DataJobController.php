<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\Datatables\Datatables;

use App\Models\DataJob;
use App\Models\Setting;
use App\Models\DataPasangBaru;
use App\Models\Karyawan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DataJobController extends Controller
{
    public function index()
    {
        $title = 'Data Job';
        $appName = Setting::first();
        $listPasangBaru = DataPasangBaru::where('status','0')
        ->whereDoesntHave('data_job')
        ->orderBy('created_at','ASC')
        ->get();
        $toDay = Carbon::now()->format('Y-m-d');
        $listKaryawan = Karyawan::whereHas('absensi', function($e){
            $hariIni = Carbon::now()->format('Y-m-d');
            $e->whereDate('created_at',$hariIni);
        })
        ->whereDoesntHave('dataJob', function($e){
            $hariIni = Carbon::now()->format('Y-m-d');
            $e->whereDate('created_at',$hariIni);
        })
        ->get();

        return view('dashboard.data_job.index', compact('title','appName','listPasangBaru','listKaryawan'));
    }

    public function getJsonDataJob(Request $request)
    {
        if ($request->ajax()) {
			$data = DataJob::select('data_jobs.id as idjob','data_jobs.kode_pasang_baru','data_jobs.created_at','data_jobs.updated_at',
            'data_pasang_barus.kode','data_pasang_barus.nama_pelanggan','data_pasang_barus.no_hp','data_pasang_barus.alamat',
            'data_pasang_barus.acuan_lokasi','data_pasang_barus.status','users.name as karyawan')
            ->join('data_pasang_barus','data_jobs.kode_pasang_baru','=','data_pasang_barus.id')
            ->leftJoin('users','data_jobs.user_id','=','users.id')
            ->orderBy('data_jobs.created_at','DESC');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('status') == '0' || $request->get('status') == '1' || $request->get('status') == '2' || $request->get('status') == '3') {
                        $instance->where('status', $request->get('status'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('data_pasang_barus.kode', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.nama_pelanggan', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.no_hp', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.alamat', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.acuan_lokasi', 'LIKE', "%$search%")
                            ->orWhere('users.name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('lll') : '';
                })

                ->addColumn('action', function($row){
					$btn = '<a href="data-job/'.$row->idjob.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    $btn = $btn.' <a href="data-job/edit/'.$row->idjob.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
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

    public function store(Request $request)
	{
		$request->validate([
			'user_id' => 'required',
            'kode_pasang_baru' => 'required',
		]);

        $data['user_id'] = $request->user_id;
		$data['kode_pasang_baru'] = $request->kode_pasang_baru;
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');

		DataJob::insert($data);
        Alert::success('Sukses','Data Job Baru berhasil disimpan');
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
        $data = DataJob::findOrFail($id);
        $listDataJob = DataJob::orderBy('created_at','DESC')->get();
        $listPasangBaru = DataPasangBaru::where('status','0')
        ->whereDoesntHave('data_job')
        ->orWhere('id',$data->kode_pasang_baru)
        ->orderBy('created_at', 'DESC')
        ->get();
        $listKaryawan = Karyawan::whereHas('absensi', function($e){
            $hariIni = Carbon::now()->format('Y-m-d');
            $e->whereDate('created_at',$hariIni);
        })->get();

        return view('dashboard.data_job.edit', compact('title','appName','data','listDataJob','listPasangBaru','listKaryawan'));
    }

    public function update(Request $request,$id)
	{
        $request->validate([
            'user_id' => 'required',
            'kode_pasang_baru' => 'required',
            'status',
		]);
        
        $data['user_id'] = $request->user_id;
		$data['kode_pasang_baru'] = $request->kode_pasang_baru;
		// $data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');
        
        $pasangbaru['status'] = $request->status;
        
        DB::transaction(function () use ($data, $pasangbaru, $id) {
            DataJob::where('id', $id)->update($data);
            $idJob = DataJob::findOrFail($id);
            DataPasangBaru::where('id', $idJob->kode_pasang_baru)->update($pasangbaru);
        });
        Alert::success('Sukses','Data Job Baru berhasil diupdate');
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
}
