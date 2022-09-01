<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str; 

use App\Models\Absensi;
use App\Models\Setting;

use Carbon\Carbon;
use DB;
use DataTables;

class AbsensiController extends Controller
{
    public function index()
    {
        if (\Auth::user()->role_id == 1) {
            $title = 'Absensi Karyawan';
            $appName = Setting::first();
            
            return view('dashboard.absensi.index', compact('title','appName'));
        } else {
            $title = 'Absensi Karyawan';
            $appName = Setting::first();
            $waktuAbsensi = Setting::select('awal_absensi','akhir_absensi')->first();
            $awalAbsensi = $waktuAbsensi->awal_absensi;
            $akhirAbsensi = $waktuAbsensi->akhir_absensi;
            $jamSekarang = Carbon::now()->format('H:i:s');
            $hariIni = Carbon::now()->format('Y-m-d');
            
            $cekAbsensi = Absensi::where('user_id',\Auth::user()->id)->where('waktu_absen',$hariIni)->whereBetween(DB::raw('TIME(created_at)'), array($awalAbsensi, $akhirAbsensi))->count();
            // dd($cekAbsensi);
            return view('dashboard.absensi.karyawan', compact('title','appName','cekAbsensi','awalAbsensi','akhirAbsensi','jamSekarang','hariIni'));
        }
    }

    public function store(){
        try {
            $karyawan = \Auth::user()->id;

            Absensi::where('user_id',$karyawan)->insert([
                'user_id' => $karyawan,
                'waktu_absen' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            Alert::success('Sukses','Terima kasih, sudah mengisi absensi hari ini');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }

    public function getJsonAbsensi(Request $request)
    {
        if ($request->ajax()) {
			$data = Absensi::select('absensis.*','users.name as namakaryawan')
			->join('users','absensis.user_id','=','users.id');
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if ($request->get('waktu_absen') != null) {
                        $instance->where('waktu_absen', $request->waktu_absen);
                    }

                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('users.name', 'LIKE', "%$search%");
                        });
                    }
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? with(new Carbon($row->created_at))->isoFormat('LLLL') : '';
                })

                ->addColumn('status', function ($row) {
                    if($row->waktu_absen){
                        return '<span class="badge badge-success">Sudah Absensi</span>';
                    }else{
                        return '<span class="badge badge-warning">Belum Absensi</span>';
                    }
                })

                ->rawColumns(['status'])
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(true);
    }

    public function delete($id){
        try {
            Absensi::where('id',$id)->delete();

            Alert::success('Sukses','Data Absensi berhasil dihapus');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }
        return redirect()->back();
    }
}
