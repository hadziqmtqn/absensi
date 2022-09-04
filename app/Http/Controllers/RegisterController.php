<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Karyawan;
use App\Models\Setting;

class RegisterController extends Controller
{
    public function index()
    {
        $title = 'Registrasi Absensi Karyawan';
        $appName = Setting::first();

        return view('register', compact('title','appName'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'name' => 'required|min:5',
            'short_name' => 'required',
            'nik',
            'phone' => 'required|unique:karyawans',
            'company_name',
            'email' => 'email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        $data['role_id'] = 2;
        $data['name'] = $request->name;
        $data['username'] = rand();
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $karyawan['short_name'] = $request->short_name;
        $karyawan['nik'] = $request->nik;
        $karyawan['phone'] = $request->phone;
        $karyawan['company_name'] = $request->company_name;
        $karyawan['created_at'] = date('Y-m-d H:i:s');
        $karyawan['updated_at'] = date('Y-m-d H:i:s');

        DB::transaction(function () use ($data, $karyawan) {
            $id_user = User::insertGetId($data);

            $karyawan['user_id'] = $id_user;
            Karyawan::insert($karyawan);
        });

        return redirect('login')->with(['success' => 'Sukses! Silahkan Login menggunakan Nomor HP/Email dan Kata Sandi']);
    }
}
