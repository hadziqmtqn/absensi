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
}
