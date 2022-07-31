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
}
