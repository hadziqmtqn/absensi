<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Setting;
use App\Models\Karyawan;

use DataTables;

class KaryawanController extends Controller
{
    public function index()
    {
        $title = 'Data Karyawan';
        $appName = Setting::first();

        return view('dashboard.karyawan.index', compact('title','appName'));
    }

    public function getJsonKaryawan(Request $request)
    {
        if ($request->ajax()) {
			$data = User::select('karyawans.*','users.name as namakaryawan','users.username','users.is_verifikasi','users.photo','users.email')
			->join('karyawans','karyawans.user_id','=','users.id');
            
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
							->orWhere('karyawans.short_name', 'LIKE', "%$search%")
							->orWhere('karyawans.phone', 'LIKE', "%$search%")
							->orWhere('karyawans.nik', 'LIKE', "%$search%")
							->orWhere('karyawans.company_name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('action', function($row){
					$btn = '<a href="profile/detail/'.$row->username.'" class="btn btn-primary">Detail</a>';
                    $btn = $btn.' <a href="profile/edit/'.$row->username.'" class="btn btn-warning">Edit</a>';
                    $btn = $btn.' <button type="button" href="profile/hapus/'.$row->id.'" class="btn btn-danger btn-hapus">Delete</button>';
                    return $btn;
                })

                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }
}
