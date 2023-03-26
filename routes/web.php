<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Middleware\VerifikasiAkun;
use App\Http\Controllers\Dashboard\KaryawanController;
use App\Http\Controllers\Dashboard\AbsensiController;
use App\Http\Controllers\Dashboard\ApiKeyController;
use App\Http\Controllers\Dashboard\DataPasangBaruController;
use App\Http\Controllers\Dashboard\DataJobController;
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

Route::middleware('guest')->group(function (){
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
    Route::get('getjsonabsensi', [AbsensiController::class, 'getJsonAbsensi'])->name('getjsonabsensi');
    // role
    Route::get('role', [RoleController::class, 'index'])->name('role.index');
    Route::get('getjsonrole', [RoleController::class, 'getJsonRole'])->name('getjsonrole');
    Route::get('role/{id}', [RoleController::class, 'detail'])->name('role.detail');
    Route::get('role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('role/edit/{id}', [RoleController::class, 'update'])->name('role.update');
    // permission
    Route::get('permission', [PermissionController::class, 'index'])->name('permission.index');
    Route::get('getjsonpermission', [PermissionController::class, 'getJsonPermission'])->name('getjsonpermission');
    Route::post('permission/store', [PermissionController::class, 'store'])->name('permission.store');
    Route::get('permission/edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit');
    Route::put('permission/edit/{id}', [PermissionController::class, 'update'])->name('permission.update');
    // setting
    Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('setting/store', [SettingController::class, 'store'])->name('setting.store');
    Route::put('setting/update/{id}', [SettingController::class, 'update'])->name('setting.update');
    // data karyawan
    Route::get('karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::get('getjsonkaryawan', [KaryawanController::class, 'getJsonKaryawan'])->name('getjsonkaryawan');
    Route::get('karyawan/trashed', [KaryawanController::class, 'trashed'])->name('karyawan.trashed');
    // Route::get('getjsonkaryawantrashed', [KaryawanController::class, 'getJsonKaryawanTrashed'])->name('getjsonkaryawantrashed');
    Route::post('getjsonkaryawantrashed', [KaryawanController::class, 'getJsonKaryawanTrashed'])->name('getjsonkaryawantrashed');
    Route::get('karyawan/{username}', [KaryawanController::class, 'detail'])->name('karyawan');
    // teknisi cadangan
    Route::get('teknisi-cadangan', [TeknisiCadanganController::class, 'index'])->name('teknisi-cadangan.index');
    Route::get('getjsonteknisicadangan', [TeknisiCadanganController::class, 'getJsonTeknisiCadangan'])->name('getjsonteknisicadangan');
    // data pasang baru
    Route::get('data-pasang-baru', [DataPasangBaruController::class, 'index'])->name('data-pasang-baru.index');
    Route::get('getjsonpasangbaru', [DataPasangBaruController::class, 'getJsonPasangBaru'])->name('getjsonpasangbaru');
    Route::get('data-pasang-baru/{kode}', [DataPasangBaruController::class, 'detail'])->name('data-pasang-baru.detail');
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
    // absen
    Route::post('absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    // whatsapp api
    Route::get('whatsapp-api', [WhatsappApiController::class, 'index'])->name('whatsapp-api.index');
    Route::post('whatsapp-api/store', [WhatsappApiController::class, 'store'])->name('whatsapp-api.store');
    Route::put('whatsapp-api/update/{id}', [WhatsappApiController::class, 'update'])->name('whatsapp-api.update');

    // api key

    Route::prefix('api-key')->group(function(){
        Route::get('/', [ApiKeyController::class, 'index'])->name('api-key.index');
        Route::put('/{id}/update', [ApiKeyController::class, 'update'])->name('api-key.update');
    });

    Route::get('forbidden', function() {
        return view('dashboard.layouts.forbidden');
    })->name('forbidden');
    
    Route::get('home', function() {
       return redirect('dashboard');
    });
});

Route::get('account_not_verified', function() {
    return view('verifikasi');
})->name('account_not_verified');

//Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');
//Route::get('register', function() {
//    return redirect('login');
//});
