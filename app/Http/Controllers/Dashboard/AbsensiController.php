<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Absensi;
use App\Models\Setting;

use Carbon\Carbon;
use DB;

class AbsensiController extends Controller
{
    public function index()
    {
        $title = 'Absensi';
        $appName = Setting::first();
        $waktuAbsensi = Setting::select('awal_absensi','akhir_absensi')->first();
        $awalAbsensi = $waktuAbsensi->awal_absensi;
        $akhirAbsensi = $waktuAbsensi->akhir_absensi;
        $jamSekarang = Carbon::now()->format('H:i:s');
        $hariIni = Carbon::now();
        
        $cekAbsensi = Absensi::where('user_id',\Auth::user()->id)->whereDate('created_at',$hariIni)->whereBetween(DB::raw('TIME(waktu_absen)'), array($awalAbsensi, $akhirAbsensi))->count();

        return view('dashboard.absensi.index', compact('title','appName','cekAbsensi','awalAbsensi','akhirAbsensi','jamSekarang','hariIni'));
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
