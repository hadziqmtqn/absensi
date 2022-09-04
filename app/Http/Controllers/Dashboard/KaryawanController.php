<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;

use App\Models\User;
use App\Models\Setting;
use App\Models\Karyawan;
use App\Models\Role;

class KaryawanController extends Controller
{
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
					$btn = '<a href="karyawan/'.$row->username.'" class="btn btn-primary" style="padding: 7px 10px">Detail</a>';
                    $btn = $btn.' <button type="button" href="karyawan/'.$row->id.'/destroy" class="btn btn-danger btn-hapus" style="padding: 7px 10px">Delete</button>';
                    return $btn;
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

                ->addColumn('action', function($row){
					$btn = '<button type="button" href="'.$row->id.'/restore" class="btn btn-warning btn-restore" style="padding: 7px 10px">Restore</button>';
                    return $btn;
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

    public function detail($username)
    {
        $title = 'Detail Karyawan';
        $appName = Setting::first();
        $profile = User::where('username',$username)->first();
        $listRole = Role::get();
        $listKaryawan = User::where('role_id',2)->get();

        return view('dashboard.karyawan.detail', compact('title','appName','profile','listRole','listKaryawan'));
    }

    public function update(Request $request, $id)
	{
		$request->validate([
            'role_id',
			'name' => 'required',
            'short_name',
            'nik',
            'phone',
            'company_name',
            'photo' => 'file|mimes:jpg,jpeg,png,svg|max:1024',
            'email' => 'email|required',
		]);

        if(Auth::user()->role_id == 1){
            $data['role_id'] = $request->role_id;
        }
        $data['name'] = $request->name;
		$data['email'] = $request->email;
        $data['short_name'] = $request->short_name;
        $data['nik'] = $request->nik;
        $data['phone'] = $request->phone;
        $data['company_name'] = $request->company_name;
        // $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $file = $request->file('photo');
        if($file){
            $nama_file = rand().'-'. $file->getClientOriginalName();
            $file->move('assets',$nama_file);
            $data['photo'] = 'assets/' .$nama_file;
        }

        User::where('id', $id)->update($data);
        Alert::success('Sukses','Profile berhasil diupdate');
		return redirect()->back();
	}

    public function update_password($username)
    {
        $title = 'Update Password Karyawan';
        $appName = Setting::first();
        $karyawan = User::where('username',$username)->first();
        $listRole = Role::get();
        $listKaryawan = User::where('role_id',2)->get();

        return view('dashboard.karyawan.update_password', compact('title','appName','karyawan','listRole','listKaryawan'));
    }

    public function password(Request $request,$id)
    {

        try {
            $password = $request->password;
            $confirm_password = $request->confirm_password;

            if($password != $confirm_password){
                Alert::error('Error','Password harus sama');
            }else{
                User::where('id',$id)->update([
                    'password'=>bcrypt($password)
                ]);
                Alert::success('Sukses','Password berhasil diubah');
            }
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        return redirect()->back();
    }

    public function verifikasi($id){
        try {
            $user = Karyawan::findOrFail($id);
            if($user->is_verifikasi == 1){
                $user->update([
                    'is_verifikasi' => 0,
                ]);
            }else{
                $user->update([
                    'is_verifikasi' => 1
                ]);
            }

            Alert::success('Sukses','Status Verifikasi Karyawan Berhasil di Update');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }

    public function destroy($id){
        try {
            $user = Karyawan::findOrFail($id);
            $user->delete();

            Alert::success('Sukses','Data Karyawan berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        return redirect()->back();
    }

    public function restore($id){
        try {
            $user = Karyawan::withTrashed()->findOrFail($id);
            if($user->trashed()){
                $user->restore();
                
                Alert::success('Sukses','Data Karyawan berhasil di restore');
            } else {
                Alert::error('Opps','Data Karyawan tidak terhapus');
            }
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        
        return redirect()->back();
    }
}
