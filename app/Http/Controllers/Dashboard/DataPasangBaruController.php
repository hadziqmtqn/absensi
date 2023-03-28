<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Models\Setting;
use App\Models\DataPasangBaru;
use Carbon\Carbon;

class DataPasangBaruController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:data-pasang-baru-list', ['only' => ['index']]);
    }
    
    public function index()
    {
        $title = 'Data Pasang Baru';
        $appName = Setting::first();

        return view('dashboard.data_pasang_baru.index', compact('title','appName'));
    }

    public function getJsonPasangBaru(Request $request)
    {
        if ($request->ajax()) {
			$data = DataPasangBaru::orderBy('created_at','DESC');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('status') == '0' || $request->get('status') == '1' || $request->get('status') == '2' || $request->get('status') == '3') {
                        $instance->where('status', $request->get('status'));
                    }

                    if ($request->get('created_at') != null) {
                        $instance->whereDate('created_at', $request->created_at);
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('data_pasang_barus.kode', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.inet', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.nama_pelanggan', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.no_hp', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.alamat', 'LIKE', "%$search%")
							->orWhere('data_pasang_barus.acuan_lokasi', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('lll') : '';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->isoFormat('lll') : '';
                })

                ->addColumn('action', function($row){
					return '<a href="data-pasang-baru/'.$row->pasang_baru_api.'/detail" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
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

    public function detail($pasangBaruApi)
    {
        $title = 'Detail Pasang Baru';
        $appName = Setting::first();
        $dataPasangBaru = DataPasangBaru::where('pasang_baru_api',$pasangBaruApi)
        ->firstOrFail();

        if($dataPasangBaru->status == 0){
            $badge = 'badge-info';
            $status = 'Waiting';
        }elseif($dataPasangBaru->status == 1){
            $badge = 'badge-primary';
            $status = 'In Progress';
        }elseif($dataPasangBaru->status == 2){
            $badge = 'badge-warning';
            $status = 'Pending';
        }elseif($dataPasangBaru->status == 3){
            $badge = 'badge-success';
            $status = 'Success';
        }

        return view('dashboard.data_pasang_baru.detail', compact('title','appName','dataPasangBaru','badge','status'));
    }
}
