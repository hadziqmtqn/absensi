<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\User;
use App\Models\Setting;
use App\Models\Karyawan;
use App\Models\Role;

class KaryawanController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:karyawan-list', ['only' => ['index','trashed','detail']]);
    }

    public function index()
    {
        $title = 'Data Karyawan';
        $appName = Setting::first();
        $karyawanAll = Karyawan::where('role_id',2)->withTrashed()->count();
        $karyawanActive = Karyawan::where('role_id',2)->count();
        $karyawanTrashed = Karyawan::where('role_id',2)->onlyTrashed()->count();

        return view('dashboard.karyawan.index', compact('title','appName','karyawanAll','karyawanActive','karyawanTrashed'));
    }

    public function getJsonKaryawan(Request $request)
    {
        if ($request->ajax()) {
            $data = Karyawan::where('role_id',2);

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('is_verifikasi') == '0' || $request->get('is_verifikasi') == '1') {
                        $instance->where('is_verifikasi', $request->get('is_verifikasi'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%")
							->orWhere('users.email', 'LIKE', "%$search%")
							->orWhere('users.short_name', 'LIKE', "%$search%")
							->orWhere('users.phone', 'LIKE', "%$search%")
							->orWhere('users.nik', 'LIKE', "%$search%")
							->orWhere('users.company_name', 'LIKE', "%$search%");
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
					return '<a href="karyawan/'.$row->username.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                })

                ->addColumn('status_verifikasi', function($row){
                    if($row->is_verifikasi){
                        return '<span class="badge badge-success">Sudah Diverifikasi</span>';
                    }else{
                        return '<span class="badge badge-warning">Belum Diverifikasi</span>';
                    }
                })

                ->addColumn('photo', function($row){
                    if($row->photo){
                        return '<img src="'.asset($row->photo).'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }else{
                        return '<img src="'.asset('theme/template/images/user.png').'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }
                })

                ->rawColumns(['action','status_verifikasi','photo'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function trashed()
    {
        $title = 'Data Karyawan Terhapus';
        $appName = Setting::first();
        $karyawanAll = Karyawan::where('role_id',2)->withTrashed()->count();
        $karyawanActive = Karyawan::where('role_id',2)->count();
        $karyawanTrashed = Karyawan::where('role_id',2)->onlyTrashed()->count();

        return view('dashboard.karyawan.trash', compact('title','appName','karyawanAll','karyawanActive','karyawanTrashed'));
    }

    public function getJsonKaryawanTrashed(Request $request)
    {
        if ($request->ajax()) {
            $data = Karyawan::where('role_id',2)->onlyTrashed();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('is_verifikasi') == '0' || $request->get('is_verifikasi') == '1') {
                        $instance->where('is_verifikasi', $request->get('is_verifikasi'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%")
							->orWhere('users.email', 'LIKE', "%$search%")
							->orWhere('users.short_name', 'LIKE', "%$search%")
							->orWhere('users.phone', 'LIKE', "%$search%")
							->orWhere('users.nik', 'LIKE', "%$search%")
							->orWhere('users.company_name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('deleted_at', function ($row) {
                    return $row->deleted_at ? with(new Carbon($row->deleted_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('status_verifikasi', function($row){
                    if($row->is_verifikasi){
                        return '<span class="badge badge-success">Sudah Diverifikasi</span>';
                    }else{
                        return '<span class="badge badge-warning">Belum Diverifikasi</span>';
                    }
                })

                ->addColumn('photo', function($row){
                    if($row->photo){
                        return '<img src="'.asset($row->photo).'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }else{
                        return '<img src="'.asset('theme/template/images/user.png').'" style="width: 30px; border-radius: 50%;" alt="image">';
                    }
                })

                ->rawColumns(['status_verifikasi','photo'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function detail($username)
    {
        $title = 'Detail Karyawan';
        $appName = Setting::first();
        $user = User::where('username', $username)
        ->first();
        $listRole = Role::get();
        $listKaryawan = User::where('role_id',2)->get();

        return view('dashboard.karyawan.detail', compact('title','appName','user','listRole','listKaryawan'));
    }
}
