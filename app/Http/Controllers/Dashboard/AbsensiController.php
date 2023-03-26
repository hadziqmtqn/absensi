<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $this->middleware('permission:absensi-list', ['only' => ['index']]);
    }

    public function index()
    {
        $title = 'Absensi Karyawan';
        $appName = Setting::first();

        return view('dashboard.absensi.index', compact('title','appName'));
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

                ->rawColumns(['status'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }
}
