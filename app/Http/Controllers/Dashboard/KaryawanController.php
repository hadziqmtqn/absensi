<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\User;
use App\Models\Setting;
use App\Models\Karyawan;
use App\Models\Role;

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

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new Carbon($row->updated_at))->isoFormat('DD MMMM YYYY') : '';
                })

                ->addColumn('action', function($row){
					$btn = '<a href="karyawan/'.$row->username.'" class="btn btn-primary">Detail</a>';
                    $btn = $btn.' <button type="button" href="karyawan/hapus/'.$row->id.'" class="btn btn-danger btn-hapus">Delete</button>';
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

        if(\Auth::user()->role_id == 1){
            $data['role_id'] = $request->role_id;
        }
        $data['name'] = $request->name;
		$data['email'] = $request->email;
		// $data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');

        $karyawan['short_name'] = $request->short_name;
        $karyawan['nik'] = $request->nik;
        $karyawan['phone'] = $request->phone;
        $karyawan['company_name'] = $request->company_name;
        // $karyawan['created_at'] = date('Y-m-d H:i:s');
        $karyawan['updated_at'] = date('Y-m-d H:i:s');

        $file = $request->file('photo');
        if($file){
            $nama_file = rand().'-'. $file->getClientOriginalName();
            $file->move('assets',$nama_file);
            $data['photo'] = 'assets/' .$nama_file;
        }

		\DB::transaction(function () use ($data, $karyawan, $id) {
            User::where('id', $id)->update($data);
            Karyawan::where('user_id', $id)->update($karyawan);
        });
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
            User::where('id',$id)->update([
                'is_verifikasi' => 1
            ]);

            Alert::success('Sukses','Karyawan ini berhasil diverifikasi');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }

    public function undo_verifikasi($id){
        try {
            User::where('id',$id)->update([
                'is_verifikasi' => 0
            ]);

            Alert::success('Sukses','Karyawan ini berhasil kembali belum diverifikasi');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }
}
