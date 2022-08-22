<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $appName = Setting::first();

        return view('dashboard.dashboard.index', compact('title','appName'));
    }
}
