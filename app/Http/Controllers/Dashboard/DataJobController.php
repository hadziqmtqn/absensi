<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\DataJob;
use App\Models\Setting;

use DataTables;
use Carbon\Carbon;

class DataJobController extends Controller
{
    public function index()
    {
        $title = 'Data Job';
        $appName = Setting::first();

        return view('dashboard.data_job.index', compact('title','appName'));
    }

    public function getJsonDataJob(Request $request)
    {
        if ($request->ajax()) {
			$data = DataJob::select('*');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('status') == '0' || $request->get('status') == '1' || $request->get('status') == '2' || $request->get('status') == '3') {
                        $instance->where('status', $request->get('status'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('data_jobs.kode', 'LIKE', "%$search%")
							->orWhere('data_jobs.nama_pelanggan', 'LIKE', "%$search%")
							->orWhere('data_jobs.no_hp', 'LIKE', "%$search%")
							->orWhere('data_jobs.alamat', 'LIKE', "%$search%")
							->orWhere('data_jobs.acuan_lokasi', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('action', function($row){
					$btn = '<a href="karyawan/'.$row->username.'" class="btn btn-primary">Detail</a>';
                    $btn = $btn.' <button type="button" href="karyawan/hapus/'.$row->iduser.'" class="btn btn-danger btn-hapus">Delete</button>';
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

                ->addColumn('foto', function($row){
                    if(!empty($row->foto)){
                        return '<img src="'.asset($row->foto).'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }
                })

                ->rawColumns(['action','status','foto'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function store(Request $request)
	{
		$request->validate([
			'kode' => 'required',
            'nama_pelanggan' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
            'acuan_lokasi' => 'required',
            'foto' => 'file|mimes:jpg,jpeg,png|max:1024'
		]);

        $data['kode'] = $request->kode;
		$data['nama_pelanggan'] = $request->nama_pelanggan;
		$data['no_hp'] = $request->no_hp;
		$data['alamat'] = $request->alamat;
		$data['acuan_lokasi'] = $request->acuan_lokasi;
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');

        $file = $request->file('foto');
        if($file){
            $nama_file = rand().'-'. $file->getClientOriginalName();
            $file->move('assets',$nama_file);
            $data['foto'] = 'assets/' .$nama_file;
        }

		DataJob::insert($data);
        Alert::success('Sukses','Data Job berhasil disimpan');
		return redirect()->back();
	}
}
