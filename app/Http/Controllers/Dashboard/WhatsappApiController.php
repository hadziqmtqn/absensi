<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OnlineApi;
use App\Models\Setting;
use App\Models\WhatsappApi;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class WhatsappApiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:whatsapp-api-list', ['only' => ['index','update']]);
    }

    public function index()
    {
        $title = 'Whatsapp API';
        $whatsappApi = WhatsappApi::first();
        $appName = Setting::first();

        return view('dashboard.whatsapp-api.index', compact('title','whatsappApi','appName'));
    }

    public function update(Request $request, $id)
	{
        $whatsappApi = WhatsappApi::findOrFail($id);

        $client = New Client();
        $onlineApi = OnlineApi::first();

        try {
            $validator = Validator::make($request->all(),[
                'domain' => ['required'],
                'api_keys' => ['required'],
                'no_hp_penerima' => ['required'],
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'domain' => $request->domain,
                'api_keys' => $request->api_keys,
                'no_hp_penerima' => $request->no_hp_penerima,
            ];

            DB::transaction(function() use ($whatsappApi, $data, $client, $onlineApi){
                $whatsappApi->update($data);

                $client->request('PUT', $onlineApi->website . '/api/whatsapp-api/' . $whatsappApi->id . '/update', [
                    'json' => $data
                ]);
            });

            Alert::success('Sukses','Whatsapp API berhasil disimpan');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }
		return redirect()->back();
	}
}
