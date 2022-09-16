<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

use App\Models\Setting;
use App\Models\TeknisiCadangan;
use Carbon\Carbon;

class TeknisiCadanganController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:teknisi-cadangan-list', ['only' => ['index']]);
    }

    public function index()
    {
        $title = 'Teknisi Cadangan';
        $appName = Setting::first();

        return view('dashboard.teknisi_cadangan.index', compact('title','appName'));
    }

    public function getJsonTeknisiCadangan(Request $request)
    {
        if ($request->ajax()) {
            $data = TeknisiCadangan::select('teknisi_cadangans.user_id','teknisi_cadangans.created_at','users.name')
            ->join('users','teknisi_cadangans.user_id','users.id')
            ->whereDate('teknisi_cadangans.created_at', Carbon::now())
            ->orderBy('created_at', 'DESC');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('teknisi_cadangans.created_at', 'LIKE', "%$search%")
                            ->orWhere('users.name', 'LIKE', "%$search%");
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
