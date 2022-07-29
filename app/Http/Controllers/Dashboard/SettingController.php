<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $title = 'Pengaturan Aplikasi';
        $cekSetting = Setting::count();
        $data = Setting::first();

        return view('dashboard.setting.index', compact('title','cekSetting','data'));
    }

    public function store(Request $request)
	{
		$request->validate([
			'application_name' => 'required',
            'email' => 'email|required',
            'no_hp',
            'logo' => 'file|mimes:jpg,jpeg,png,svg|max:1024'
		]);

        $data['application_name'] = $request->application_name;
		$data['email'] = $request->email;
		$data['no_hp'] = $request->no_hp;
		$data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');

        $file = $request->file('logo');
        if($file){
            $nama_file = rand().'-'. $file->getClientOriginalName();
            $file->move('assets',$nama_file);
            $data['logo'] = 'assets/' .$nama_file;
        }

		Setting::insert($data);
		return redirect()->back()->with('success','Pengaturan Aplikasi berhasil disimpan');
	}

    public function update(Request $request, $id)
	{
		$request->validate([
			'application_name' => 'required',
            'email' => 'email|required',
            'no_hp',
            'logo' => 'file|mimes:jpg,jpeg,png,svg|max:1024'
		]);

        $data['application_name'] = $request->application_name;
		$data['email'] = $request->email;
		$data['no_hp'] = $request->no_hp;
		// $data['created_at'] = date('Y-m-d H:i:s');
		$data['updated_at'] = date('Y-m-d H:i:s');

        $file = $request->file('logo');
        if($file){
            $nama_file = rand().'-'. $file->getClientOriginalName();
            $file->move('assets',$nama_file);
            $data['logo'] = 'assets/' .$nama_file;
        }

		Setting::where('id',$id)->update($data);
		return redirect()->back()->with('success','Pengaturan Aplikasi berhasil diupdate');
	}
}