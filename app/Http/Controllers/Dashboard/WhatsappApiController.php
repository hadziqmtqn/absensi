<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WhatsappApi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WhatsappApiController extends Controller
{
    public function index()
    {
        $title = 'Whatsapp API';
        $data = WhatsappApi::first();
        $cekWhatsappApi = WhatsappApi::count();
        $appName = Setting::first();

        return view('dashboard.whatsapp-api.index', compact('title','data','cekWhatsappApi','appName'));
    }

    public function store(Request $request)
	{
        try {
            $request->validate([
                'domain' => 'required',
                'api_keys' => 'required',
                'no_hp_penerima' => 'required',
            ]);
    
            $data['domain'] = $request->domain;
            $data['api_keys'] = $request->api_keys;
            $data['no_hp_penerima'] = $request->no_hp_penerima;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
    
            WhatsappApi::insert($data);

            Alert::success('Sukses','Whatsapp API berhasil disimpan');
        } catch (\Throwable $th) {
            Alert::error('Error',$th->getMessage());
        }
		return redirect()->back();
	}

    public function update(Request $request, $id)
	{
        try {
            $request->validate([
                'domain' => 'required',
                'api_keys' => 'required',
                'no_hp_penerima' => 'required',
            ]);
    
            $data['domain'] = $request->domain;
            $data['api_keys'] = $request->api_keys;
            $data['no_hp_penerima'] = $request->no_hp_penerima;
            // $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
    
            WhatsappApi::where('id',$id)->update($data);

            Alert::success('Sukses','Whatsapp API berhasil disimpan');
        } catch (\Throwable $th) {
            Alert::error('Error',$th->getMessage());
        }
		return redirect()->back();
	}
}
