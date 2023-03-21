<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class ApiKeyController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:api-key-list', ['only' => ['index','update']]);
    }

    public function index()
    {
        $title = 'API Key';
        $appName = Setting::first();
        $apiKey = ApiKey::first();

        return view('dashboard.api_key.index', compact('title','appName','apiKey'));
    }

    public function update(Request $request, $id)
	{
        $apiKey = ApiKey::findOrFail($id);
        
        try {
            $request->validate([
                'enkripsi' => ['required'],
                'domain' => ['required'],
                'api_key' => ['nullable'],
            ]);

            $api = !is_null($request->api_key) ? Hash::make($request->api_key) : $apiKey->api_key;
            
            $data = [
                'enkripsi' => $request->enkripsi,
                'domain' => $request->domain,
                'api_key' => $api
            ];
            
            $apiKey->update($data);

            Alert::success('Sukses','API Key berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops!','Data Error');
        }

		return redirect()->back();
	}
}
