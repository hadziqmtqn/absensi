<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DataJob;
use App\Models\DataPasangBaru;
use App\Models\Karyawan;
use Illuminate\Http\Request;

use App\Models\Setting;
use App\Models\TeknisiCadangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Stevebauman\Location\Facades\Location;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->role_id == 1){
            $title = 'Dashboard';
            $appName = Setting::first();
            
            $months = [];
            for ($m=1; $m<=12; $m++) {
                $months[] = date('F', mktime(0,0,0,$m, 1, date('Y')));
            }

            $pasangBaru = [];
            $dataJobPending = [];
            $dataJobSuccess = [];
            foreach ($months as $key => $value) {
                $pasangBaru[] = DataPasangBaru::where(DB::raw("DATE_FORMAT(created_at, '%M')"),$value)
                ->whereYear('created_at', date('Y'))
                ->count();
                // job pending
                $dataJobPending[] = DataJob::where(DB::raw("DATE_FORMAT(created_at, '%M')"),$value)
                ->whereHas('dataPasangBaru', function($e){
                    $e->where('status','2');
                })
                ->whereYear('created_at', date('Y'))
                ->count();
                // job success
                $dataJobSuccess[] = DataJob::where(DB::raw("DATE_FORMAT(created_at, '%M')"),$value)
                ->whereHas('dataPasangBaru', function($e){
                    $e->where('status','3');
                })
                ->whereYear('created_at', date('Y'))
                ->count();
            }

            $today = Carbon::now()->format('Y-m-d');
            $pasangBaruToday = DataPasangBaru::whereDate('created_at', $today)->count();
            $dataJobToday = DataJob::whereDate('created_at', $today)->count();
            $absensiToday = Absensi::whereDate('created_at', $today)->count();
            $totalKaryawan = User::where('role_id',2)->count();

            $ip = $request->ip(); // Dynamic IP address
            // $ip = '182.2.41.105'; /* Static IP address */
            $currentUserInfo = Location::get($ip);

            // dd($currentUserInfo);

            return view('dashboard.dashboard.index', compact('title','appName','months','pasangBaru','dataJobPending','dataJobSuccess','today','pasangBaruToday','dataJobToday','absensiToday','totalKaryawan','currentUserInfo'));
        }else{
            $search = $request->search;

            $title = 'Dashboard';
            $subTitle = 'Data Job';
            $appName = Setting::first();
            $karyawan = Karyawan::where('id',Auth::user()->id)->firstOrFail();

            $hariIni = Carbon::now()->format('Y-m-d');
            $waktuAbsensi = Setting::select('awal_absensi','akhir_absensi')->first();
            $awalAbsensi = $waktuAbsensi->awal_absensi;
            $akhirAbsensi = $waktuAbsensi->akhir_absensi;
            $jamSekarang = Carbon::now()->format('H:i:s');
            
            $cekAbsensi = Absensi::where('user_id',Auth::user()->id)->where('waktu_absen',$hariIni)->whereBetween(DB::raw('TIME(created_at)'), array($awalAbsensi, $akhirAbsensi))->count();

            if($request->search){
                $listJobs = DataJob::where('user_id',$karyawan->id)
                ->select('data_jobs.created_at as create_job',
                'data_pasang_barus.id','data_pasang_barus.kode','data_pasang_barus.nama_pelanggan','data_pasang_barus.no_hp',
                'data_pasang_barus.alamat','data_pasang_barus.acuan_lokasi','data_pasang_barus.status','data_pasang_barus.inet','data_pasang_barus.foto')
                ->join('data_pasang_barus','data_jobs.kode_pasang_baru','=','data_pasang_barus.id')
                ->where('data_jobs.created_at','like','%'.$search.'%')
                ->orderBy('data_jobs.created_at','DESC')
                ->get();
            }else{
                $listJobs = DataJob::where('user_id',$karyawan->id)
                ->select('data_jobs.created_at as create_job',
                'data_pasang_barus.id','data_pasang_barus.kode','data_pasang_barus.nama_pelanggan','data_pasang_barus.no_hp',
                'data_pasang_barus.alamat','data_pasang_barus.acuan_lokasi','data_pasang_barus.status','data_pasang_barus.inet','data_pasang_barus.foto')
                ->join('data_pasang_barus','data_jobs.kode_pasang_baru','=','data_pasang_barus.id')
                ->whereDate('data_jobs.created_at',$hariIni)
                ->where('data_jobs.created_at','like','%'.$search.'%')
                ->orderBy('data_jobs.created_at','DESC')
                ->get();
            }

            return view('dashboard.dashboard.karyawan', compact('title','appName','subTitle','listJobs','search','cekAbsensi','awalAbsensi','akhirAbsensi','jamSekarang','hariIni'));
        }
    }

    public function inProgress($id){
        try {
            DataPasangBaru::where('id',$id)->update([
                'status' => '1',
            ]);

            Alert::success('Sukses','Status Job In Progress');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }

    public function pending($id){
        try {
            DataPasangBaru::where('id',$id)->update([
                'status' => '2',
            ]);

            Alert::success('Sukses','Status Job Pending');
        } catch (\Exception $e) {
            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }

    public function success($id){
        try {
            $iduser = Auth::user()->id;
            $cekNonJob = Karyawan::whereHas('absensi', function($e){
                $e->whereDate('created_at', date('Y-m-d'));
            })
            ->whereDoesntHave('dataJob')
            ->count();
            
            $cekPasangBaru = DataPasangBaru::select('id')
            ->whereDoesntHave('data_job')
            ->count();
            
            DB::beginTransaction();

            DataPasangBaru::where('id',$id)->update([
                'status' => '3',
            ]);           

            if($cekNonJob < 1 && $cekPasangBaru > 0){
                $pasangBaru = DataPasangBaru::select('id')
                ->whereDoesntHave('data_job')
                ->first();

                $dataJob['user_id'] = $iduser;
                $dataJob['kode_pasang_baru'] = $pasangBaru->id;
                $dataJob['created_at'] = date('Y-m-d H:i:s');
                $dataJob['updated_at'] = date('Y-m-d H:i:s');
    
                DataJob::insert($dataJob);
            }else{
                $data['user_id'] = $iduser;
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['updated_at'] = date('Y-m-d H:i:s');

                TeknisiCadangan::insert($data);
            }

            DB::commit();
            
            Alert::success('Sukses','Status Job Success');
        } catch (\Exception $e) {
            DB::rollback();

            Alert::error('Error',$e->getMessage());
        }

        return redirect()->back();
    }
}
