<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $title = 'Pengaturan Aplikasi';
        $cekSetting = Setting::count();
        $data = Setting::first();

        return view('dashboard.setting.index', compact('title','cekSetting','data'));
    }
}
