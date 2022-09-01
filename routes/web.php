<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Middleware\VerifikasiAkun;
use App\Http\Controllers\Dashboard\KaryawanController;
use App\Http\Controllers\Dashboard\AbsensiController;
use App\Http\Controllers\Dashboard\DataPasangBaruController;
use App\Http\Controllers\Dashboard\DataJobController;

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
    return view('auth.login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('registration', [RegisterController::class, 'index'])->name('registration');
    Route::post('registration', [RegisterController::class, 'store'])->name('registration.store');
});

Route::middleware(['auth',VerifikasiAkun::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    // profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/update_password', [ProfileController::class, 'update_password'])->name('profile.update_password');
    Route::put('profile/password/{id}', [ProfileController::class, 'password'])->name('profile.password');
    // absensi
    Route::get('absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('getjsonabsensi', [AbsensiController::class, 'getJsonAbsensi'])->name('getjsonabsensi');
    Route::delete('absensi/hapus/{id}',[AbsensiController::class, 'delete'])->name('absensi.hapus');

    Route::middleware([Admin::class])->group(function(){
        // setting
        Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
        Route::post('setting/store', [SettingController::class, 'store'])->name('setting.store');
        Route::put('setting/update/{id}', [SettingController::class, 'update'])->name('setting.update');
        // data karyawan
        Route::get('karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
        Route::post('getjsonkaryawan', [KaryawanController::class, 'getJsonKaryawan'])->name('getjsonkaryawan');
        Route::get('karyawan/{username}', [KaryawanController::class, 'detail'])->name('karyawan');
        Route::put('karyawan/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
        Route::get('karyawan/{username}/katasandi', [KaryawanController::class, 'update_password'])->name('karyawan.katasandi');
        Route::put('karyawan/password/{id}', [KaryawanController::class, 'password'])->name('karyawan.password');
        Route::get('karyawan/{id}/verifikasi', [KaryawanController::class, 'verifikasi'])->name('karyawan.verifikasi');
        Route::get('karyawan/{id}/undo_verifikasi', [KaryawanController::class, 'undo_verifikasi'])->name('karyawan.undo_verifikasi');
        Route::delete('karyawan/hapus/{id}', [KaryawanController::class, 'delete'])->name('karyawan.hapus');
        // data pasang baru
        Route::get('data_pasang_baru', [DataPasangBaruController::class, 'index'])->name('data_pasang_baru.index');
        Route::post('data_pasang_baru/store', [DataPasangBaruController::class, 'store'])->name('data_pasang_baru.store');
        Route::post('getjsonpasangbaru', [DataPasangBaruController::class, 'getJsonPasangBaru'])->name('getjsonpasangbaru');
        Route::get('data_pasang_baru/{kode}', [DataPasangBaruController::class, 'detail'])->name('data_pasang_baru.detail');
        Route::get('data_pasang_baru/edit/{kode}', [DataPasangBaruController::class, 'edit'])->name('data_pasang_baru.edit');
        Route::put('data_pasang_baru/edit/{id}', [DataPasangBaruController::class, 'update'])->name('data_pasang_baru.update');
        Route::delete('data_pasang_baru/hapus/{id}', [DataPasangBaruController::class, 'delete'])->name('data_pasang_baru.hapus');
        // data job
        Route::get('data_job', [DataJobController::class, 'index'])->name('data_job.index');
        Route::post('data_job/store', [DataJobController::class, 'store'])->name('data_job.store');
        Route::post('getjsondatajob', [DataJobController::class, 'getJsonDataJob'])->name('getjsondatajob');
        Route::get('data_job/{id}', [DataJobController::class, 'detail'])->name('data_job.detail');
        Route::get('data_job/edit/{id}', [DataJobController::class, 'edit'])->name('data_job.edit');
        Route::put('data_job/edit/{id}', [DataJobController::class, 'update'])->name('data_job.update');
        Route::delete('data_job/hapus/{id}', [DataJobController::class, 'delete'])->name('data_job.hapus');
    });

    Route::get('forbidden', function() {
        return view('dashboard.layouts.forbidden');
    })->name('forbidden');
});

Route::get('account_not_verified', function() {
    return view('verifikasi');
})->name('account_not_verified');

Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('home', function() {
    return redirect('dashboard');
});
Route::get('register', function() {
    return redirect('registration');
});
