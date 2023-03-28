<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\RegistrasiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Middleware\VerifikasiAkun;
use App\Http\Controllers\Dashboard\KaryawanController;
use App\Http\Controllers\Dashboard\AbsensiController;
use App\Http\Controllers\Dashboard\DataPasangBaruController;
use App\Http\Controllers\Dashboard\DataJobController;
use App\Http\Controllers\Dashboard\OnlineApiController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\TeknisiCadanganController;
use App\Http\Controllers\Dashboard\WhatsappApiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login.index');
});

Route::group(['middleware' => ['guest','log.route.api']], function () {
    Route::get('login', [LoginController::class, 'index'])->name('login.index');
    Route::post('get-login', [LoginController::class, 'login'])->name('login.get-login');
    Route::get('register', function (){
        return redirect()->route('login');
    });
});

Route::middleware(['auth',VerifikasiAkun::class])->group(function () {
    // auth
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('in-progress/{id}', [DashboardController::class, 'inProgress'])->name('in-progress');
    Route::get('pending/{id}', [DashboardController::class, 'pending'])->name('pending');
    Route::get('success/{id}', [DashboardController::class, 'success'])->name('success');
    // profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/update_password', [ProfileController::class, 'update_password'])->name('profile.update_password');
    Route::put('profile/password/{id}', [ProfileController::class, 'password'])->name('profile.password');
    // absensi
    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('absensi/add_absensi', [AbsensiController::class, 'add_absensi'])->name('absensi.add_absensi');
    Route::get('getjsonabsensi', [AbsensiController::class, 'getJsonAbsensi'])->name('getjsonabsensi');
    Route::delete('absensi/hapus/{id}',[AbsensiController::class, 'delete'])->name('absensi.hapus');
    Route::get('absensi/edit/{id}', [AbsensiController::class, 'edit'])->name('absensi.edit');
    Route::put('absensi/edit/{id}', [AbsensiController::class, 'update'])->name('absensi.update');
    // role
    Route::get('role', [RoleController::class, 'index'])->name('role.index');
    Route::get('getjsonrole', [RoleController::class, 'getJsonRole'])->name('getjsonrole');
    Route::get('role/{id}', [RoleController::class, 'detail'])->name('role.detail');
    Route::get('role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('role/edit/{id}', [RoleController::class, 'update'])->name('role.update');
    // permission
    Route::prefix('permission')->group(function(){
        Route::get('/', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('/getjsonpermission', [PermissionController::class, 'getJsonPermission'])->name('permission.getjsonpermission');
        Route::post('/store', [PermissionController::class, 'store'])->name('permission.store');
        Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
        Route::put('/edit/{id}', [PermissionController::class, 'update'])->name('permission.update');
        Route::delete('/{id}/delete', [PermissionController::class, 'delete'])->name('permission.delete');
    });
    // setting
    Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('setting/store', [SettingController::class, 'store'])->name('setting.store');
    Route::put('setting/update/{id}', [SettingController::class, 'update'])->name('setting.update');
    // data karyawan
    Route::get('karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('getjsonkaryawan', [KaryawanController::class, 'getJsonKaryawan'])->name('getjsonkaryawan');
    Route::get('karyawan/trashed', [KaryawanController::class, 'trashed'])->name('karyawan.trashed');
    Route::post('getjsonkaryawantrashed', [KaryawanController::class, 'getJsonKaryawanTrashed'])->name('getjsonkaryawantrashed');
    Route::get('karyawan/{username}', [KaryawanController::class, 'detail'])->name('karyawan');
    Route::put('karyawan/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::get('karyawan/{username}/katasandi', [KaryawanController::class, 'update_password'])->name('karyawan.katasandi');
    Route::put('karyawan/password/{id}', [KaryawanController::class, 'password'])->name('karyawan.password');
    Route::get('karyawan/{id}/verifikasi', [KaryawanController::class, 'verifikasi'])->name('karyawan.verifikasi');
    Route::delete('karyawan/{id}/destroy', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    Route::post('karyawan/{id}/restore', [KaryawanController::class, 'restore'])->name('karyawan.restore');
    Route::delete('karyawan/{id}/delete-permanen', [KaryawanController::class, 'deletePermanen'])->name('karyawan.delete-permanen');
    // teknisi cadangan
    Route::get('teknisi-cadangan', [TeknisiCadanganController::class, 'index'])->name('teknisi-cadangan.index');
    Route::get('getjsonteknisicadangan', [TeknisiCadanganController::class, 'getJsonTeknisiCadangan'])->name('getjsonteknisicadangan');
    // data pasang baru
    Route::prefix('data-pasang-baru')->group(function(){
        Route::get('/', [DataPasangBaruController::class, 'index'])->name('data-pasang-baru.index');
        Route::post('/store', [DataPasangBaruController::class, 'store'])->name('data-pasang-baru.store');
        Route::post('/getjsonpasangbaru', [DataPasangBaruController::class, 'getJsonPasangBaru'])->name('data-pasang-baru.getjsonpasangbaru');
        Route::get('/{pasang_baru_api}/detail', [DataPasangBaruController::class, 'detail'])->name('data-pasang-baru.detail');
        Route::get('/edit/{pasang_baru_api}', [DataPasangBaruController::class, 'edit'])->name('data-pasang-baru.edit');
        Route::put('/edit/{id}', [DataPasangBaruController::class, 'update'])->name('data-pasang-baru.update');
        Route::delete('/hapus/{id}', [DataPasangBaruController::class, 'delete'])->name('data-pasang-baru.hapus');
        Route::put('/{id}/update-status', [DataPasangBaruController::class, 'updateStatus'])->name('data-pasang-baru.update-status');
    });
    // data job
    Route::get('data-job', [DataJobController::class, 'index'])->name('data-job.index');
    Route::post('data-job/store', [DataJobController::class, 'store'])->name('data-job.store');
    Route::get('getjsondatajob', [DataJobController::class, 'getJsonDataJob'])->name('getjsondatajob');
    Route::get('data-job/{id}', [DataJobController::class, 'detail'])->name('data-job.detail');
    Route::get('data-job/edit/{id}', [DataJobController::class, 'edit'])->name('data-job.edit');
    Route::put('data-job/edit/{id}', [DataJobController::class, 'update'])->name('data-job.update');
    Route::delete('data-job/hapus/{id}', [DataJobController::class, 'delete'])->name('data-job.hapus');
    // teknisi non job
    Route::get('teknisi-non-job', [DataJobController::class, 'teknisiNonJob'])->name('teknisi-non-job.index');
    Route::get('getjsonteknisinonjob', [DataJobController::class, 'getJsonTeknisiNonJob'])->name('getjsonteknisinonjob');
    // whatsapp api
    Route::get('whatsapp-api', [WhatsappApiController::class, 'index'])->name('whatsapp-api.index');
    Route::post('whatsapp-api/store', [WhatsappApiController::class, 'store'])->name('whatsapp-api.store');
    Route::put('whatsapp-api/update/{id}', [WhatsappApiController::class, 'update'])->name('whatsapp-api.update');

    Route::middleware('api.key')->group(function(){
        // register karyawan
        Route::prefix('registrasi')->group(function(){
            Route::get('/', [RegistrasiController::class, 'index'])->name('registrasi.index');
            Route::post('/store', [RegistrasiController::class, 'store'])->name('registrasi.store');
        });
    });

    Route::get('forbidden', function() {
        return view('dashboard.layouts.forbidden');
    })->name('forbidden');
    
    Route::get('home', function() {
       return redirect('dashboard');
    });

    Route::prefix('online-api')->group(function(){
        Route::get('/', [OnlineApiController::class, 'index'])->name('online-api.index');
        Route::put('/{id}/update', [OnlineApiController::class, 'update'])->name('online-api.update');
    });
});

Route::get('account_not_verified', function() {
    return view('verifikasi');
})->name('account_not_verified');