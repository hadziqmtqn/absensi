<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DataJob;
use App\Models\Karyawan;
use Illuminate\Http\Request;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->role_id == 1){
            $title = 'Dashboard';
            $appName = Setting::first();
    
            return view('dashboard.dashboard.index', compact('title','appName'));
        }else{
            $search = $request->search;

            $title = 'Dashboard';
            $subTitle = 'Data Job';
            $appName = Setting::first();
            $karyawan = Karyawan::where('id',Auth::user()->id)->firstOrFail();

            $hariIni = Carbon::now()->format('Y-m-d');

            if($request->search){
                $listJobs = DataJob::where('user_id',$karyawan->id)
                ->select('data_jobs.created_at as create_job',
                'data_pasang_barus.id','data_pasang_barus.kode','data_pasang_barus.nama_pelanggan','data_pasang_barus.no_hp',
                'data_pasang_barus.alamat','data_pasang_barus.acuan_lokasi','data_pasang_barus.status','data_pasang_barus.inet','data_pasang_barus.foto')
                ->join('data_pasang_barus','data_jobs.kode_pasang_baru','=','data_pasang_barus.id')
                ->where('data_jobs.created_at','like','%'.$search.'%')
                ->get();
            }else{
                $listJobs = DataJob::where('user_id',$karyawan->id)
                ->select('data_jobs.created_at as create_job',
                'data_pasang_barus.id','data_pasang_barus.kode','data_pasang_barus.nama_pelanggan','data_pasang_barus.no_hp',
                'data_pasang_barus.alamat','data_pasang_barus.acuan_lokasi','data_pasang_barus.status','data_pasang_barus.inet','data_pasang_barus.foto')
                ->join('data_pasang_barus','data_jobs.kode_pasang_baru','=','data_pasang_barus.id')
                ->whereDate('data_jobs.created_at',$hariIni)
                ->where('data_jobs.created_at','like','%'.$search.'%')
                ->get();
            }

            return view('dashboard.dashboard.karyawan', compact('title','appName','subTitle','listJobs','search'));
        }
    }
}
