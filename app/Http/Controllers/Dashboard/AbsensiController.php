<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Absensi;
use App\Models\Setting;

use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $title = 'Absensi';
        $appName = Setting::first();
        $cekDateNow = Carbon::now()->format('Y-m-d');
        // dd($cekDateNow);
        $cekAbsensi = Absensi::where('user_id',\Auth::user()->id)->whereDate('waktu_absen','=', $cekDateNow)->count();
        // dd($cekAbsensi);

        return view('dashboard.absensi.index', compact('title','appName','cekAbsensi'));
    }

    public function store(){
        try {
            $karyawan = \Auth::user()->id;

            Absensi::where('user_id',$karyawan)->insert([
                'user_id' => $karyawan,
                'waktu_absen' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            \Session::flash('success','Terima kasih, sudah mengisi absensi hari ini');
        } catch (\Exception $e) {
            \Session::flash('error',$e->getMessage());
        }

        return redirect()->back();
    }
}
