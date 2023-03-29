<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Setting;

class SettingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:setting-list', ['only' => ['index','update']]);
    }

    public function index()
    {
        $title = 'Pengaturan Aplikasi';
        $appName = Setting::first();

        return view('dashboard.setting.index', compact('title','appName'));
    }
}
