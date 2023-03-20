<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\WhatsappApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class WhatsappApiController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:whatsapp-api-create|whatsapp-api-edit', ['only' => ['index','show']]);
        $this->middleware('permission:whatsapp-api-create', ['only' => ['create','store']]);
        $this->middleware('permission:whatsapp-api-edit', ['only' => ['edit','update']]);
    }

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
            $validator = Validator::make($request->all(),[
                'domain' => 'required',
                'api_keys' => 'required',
                'no_hp_penerima' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'domain' => $request->domain,
                'api_keys' => $request->api_keys,
                'no_hp_penerima' => $request->no_hp_penerima,
            ];

            WhatsappApi::create($data);

            Alert::success('Sukses','Whatsapp API berhasil disimpan');
        } catch (\Throwable $th) {
            Alert::error('Error',$th->getMessage());
        }
		return redirect()->back();
	}

    public function update(Request $request, $id)
	{
        try {
            $validator = Validator::make($request->all(),[
                'domain' => 'required',
                'api_keys' => 'required',
                'no_hp_penerima' => 'required',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $data = [
                'domain' => $request->domain,
                'api_keys' => $request->api_keys,
                'no_hp_penerima' => $request->no_hp_penerima,
            ];

            WhatsappApi::where('id',$id)->update($data);

            Alert::success('Sukses','Whatsapp API berhasil disimpan');
        } catch (\Throwable $th) {
            Alert::error('Error',$th->getMessage());
        }
		return redirect()->back();
	}
}
