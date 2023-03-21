<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OnlineApi;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class OnlineApiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:online-api-list', ['only' => ['index','update']]);
    }

    public function index()
    {
        $title = 'Online API';
        $appName = Setting::first();
        $onlineApi = OnlineApi::first();

        return view('dashboard.online_api.index', compact('title','appName','onlineApi'));
    }

    public function update(Request $request, $id)
	{
        $onlineApi = OnlineApi::findOrFail($id);
        
        try {
            $request->validate([
                'website' => ['required'],
            ]);
            
            $data = [
                'website' => $request->website
            ];
            
            $onlineApi->update($data);

            Alert::success('Sukses','Online API berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops!','Data Error');
        }

		return redirect()->back();
	}
}
