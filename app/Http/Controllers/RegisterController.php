<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
        ]);

        $user = User::create([
            'role_id' => 2,
            'name' => $request->input('name'),
            'username' => rand(),
            'short_name' => $request->input('short_name'),
            'nik' => $request->input('nik'),
            'phone' => $request->input('phone'),
            'company_name' => $request->input('company_name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $user->assignRole('2');

        return redirect('login')->with(['success' => 'Sukses! Silahkan Login menggunakan Nomor HP/Email dan Kata Sandi']);
    }
}
