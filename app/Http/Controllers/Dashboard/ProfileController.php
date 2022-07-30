<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Setting;

class ProfileController extends Controller
{
    public function index()
    {
        $title = 'Profile Setting';
        $appName = Setting::first();

        return view('dashboard.profile.index', compact('title','appName'));
    }
}
