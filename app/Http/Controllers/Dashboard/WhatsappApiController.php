<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WhatsappApi;

class WhatsappApiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:whatsapp-api-list', ['only' => ['index']]);
    }

    public function index()
    {
        $title = 'Whatsapp API';
        $whatsappApi = WhatsappApi::first();
        $appName = Setting::first();

        return view('dashboard.whatsapp-api.index', compact('title','whatsappApi','appName'));
    }
}
