<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
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
            'phone' => 'required|unique:users',
            'company_name',
            'email' => 'email|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password'
        ]);

        $data['role_id'] = 2;
        $data['name'] = $request->name;
        $data['username'] = rand();
        $data['short_name'] = $request->short_name;
        $data['nik'] = $request->nik;
        $data['phone'] = $request->phone;
        $data['company_name'] = $request->company_name;
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        User::insert($data);

        return redirect('login')->with(['success' => 'Sukses! Silahkan Login menggunakan Nomor HP/Email dan Kata Sandi']);
    }
}
