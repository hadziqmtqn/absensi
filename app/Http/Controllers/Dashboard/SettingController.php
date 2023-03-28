<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:setting-list', ['only' => ['index','update']]);
    }

    public function index()
    {
        $title = 'Pengaturan Aplikasi';
        $cekSetting = Setting::count();
        $appName = Setting::first();

        return view('dashboard.setting.index', compact('title','cekSetting','appName'));
    }

    public function update(Request $request, $id)
	{
        $appName = Setting::findOrFail($id);

        try {
            $validator = Validator::make($request->all(),[
                'application_name' => ['required'],
                'description' => ['nullable'],
                'email' => ['email', 'required'],
                'no_hp' => ['nullable'],
                'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:1024'],
                'awal_absensi' => ['nullable'],
                'akhir_absensi' => ['nullable']
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            
            $file = $request->file('logo');
            if($file){
                $nama_file = rand().'-'. $file->getClientOriginalName();
                $file->move('assets',$nama_file);
                $data['logo'] = 'assets/' .$nama_file;
            }
    
            $data = [
                'application_name' => $request->application_name,
                'description' => $request->description,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'awal_absensi' => $request->awal_absensi,
                'akhir_absensi' => $request->akhir_absensi,
            ];
            
            $appName->update($data);

            Alert::success('Sukses','Pengaturan Aplikasi berhasil diupdate');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            Alert::error('Oops', 'Data Error');
        }

		return redirect()->back();
	}
}
