<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\DataJob;
use App\Models\Setting;
use App\Models\DataPasangBaru;
use App\Models\Absensi;

use DataTables;
use Carbon\Carbon;

class DataJobController extends Controller
{
    public function index()
    {
        $title = 'Data Job';
        $appName = Setting::first();
        $toDay = Carbon::now()->format('Y-m-d');
        $listPasangBaru = DataPasangBaru::whereDate('created_at', $toDay)->orderBy('created_at','DESC')->get();
        $listAbsensi = Absensi::select('absensis.id','absensis.user_id','absensis.created_at as tgl_absen','users.name')
        ->join('users','absensis.user_id','=','users.id')
        ->whereDate('absensis.created_at',$toDay)
        ->orderBy('absensis.created_at','DESC')
        ->get();

        return view('dashboard.data_job.index', compact('title','appName','listPasangBaru','listAbsensi'));
    }

    public function getJsonDataJob(Request $request)
    {
        if ($request->ajax()) {
			$data = DataJob::select('data_jobs.id as idjob','data_jobs.kode_pasang_baru','data_jobs.created_at','data_jobs.updated_at','data_pasang_barus.kode','data_pasang_barus.nama_pelanggan','data_pasang_barus.no_hp','data_pasang_barus.alamat',
            'data_pasang_barus.acuan_lokasi','data_pasang_barus.status','users.name')
            ->join('data_pasang_barus','data_jobs.kode_pasang_baru','=','data_pasang_barus.id')
            ->leftJoin('absensis','data_jobs.user_id','=','absensis.id')
            ->leftJoin('users','absensis.user_id','=','users.id')
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
					$btn = '<a href="data_pasang_baru/'.$row->id.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    $btn = $btn.' <a href="data_pasang_baru/edit/'.$row->id.'" class="btn btn-warning" style="padding: 7px 10px">Edit</a>';
                    $btn = $btn.' <button type="button" href="data_pasang_baru/hapus/'.$row->id.'" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    return $btn;
                })

                ->addColumn('status', function($row){
                    if($row->status == 0){
                        return '<span class="badge badge-info">Open</span>';
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
}
