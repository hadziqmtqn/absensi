<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Setting;

class KaryawanController extends Controller
{
    public function index()
    {
        $title = 'Data Karyawan';
        $appName = Setting::first();

        return view('dashboard.karyawan.index', compact('title','appName'));
    }

    public function getJsonSekolah(Request $request)
    {
        if ($request->ajax()) {
			$data = User::select('data_sekolahs.*','users.name as namasiswa','users.id_registrasi','kabupatens.nama as kabupaten')
			->join('users','data_sekolahs.user_id','=','users.id')
			->leftJoin('kabupatens','data_sekolahs.kabupaten','=','kabupatens.id');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
					if ($request->get('kabupaten') != null) {
                        $instance->where('data_sekolahs.kabupaten', $request->kabupaten);
                    }

					if ($request->get('akreditasi') == 'A' || $request->get('akreditasi') == 'B' || $request->get('akreditasi') == 'C' || $request->get('akreditasi') == 'Belum Terakreditasi') {
                        $instance->where('akreditasi', $request->get('akreditasi'));
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%")
							->orWhere('data_sekolahs.nama', 'LIKE', "%$search%")
							->orWhere('data_sekolahs.alamat', 'LIKE', "%$search%")
							->orWhere('kabupatens.nama', 'LIKE', "%$search%")
							->orWhere('data_sekolahs.no_telp', 'LIKE', "%$search%")
							->orWhere('data_sekolahs.akreditasi', 'LIKE', "%$search%")
							->orWhere('data_sekolahs.alumni', 'LIKE', "%$search%")
							->orWhere('data_sekolahs.prestasi_sekolah', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('action', function($row){
					$siswa = User::where('id',$row->user_id)->first();
					$btn = '<a href="peserta/detail/'.$siswa->username.'" class="btn btn-primary btn-xs">Detail</a>';
                    $btn = $btn.' <a href="data_sekolah/edit/'.$siswa->username.'" class="btn btn-warning btn-xs">Edit</a>';
                    $btn = $btn.' <button type="button" href="data_sekolah/hapus/'.$row->id.'" class="btn btn-danger btn-xs btn-hapus">Delete</button>';
                    return $btn;
                })

                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }
}
