<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Middleware\VerifikasiAkun;
use App\Http\Controllers\Dashboard\KaryawanController;
use App\Http\Controllers\Dashboard\AbsensiController;
use App\Http\Controllers\Dashboard\DataPasangBaruController;
use App\Http\Controllers\Dashboard\DataJobController;
use App\Http\Controllers\Dashboard\RoleController;

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
    return redirect('login');
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
    Route::get('absensi/add_absensi', [AbsensiController::class, 'add_absensi'])->name('absensi.add_absensi');
    Route::post('getjsonabsensi', [AbsensiController::class, 'getJsonAbsensi'])->name('getjsonabsensi');
    Route::delete('absensi/hapus/{id}',[AbsensiController::class, 'delete'])->name('absensi.hapus');

    Route::get('role', [RoleController::class, 'index'])->name('role.index');
    Route::post('getjsonrole', [RoleController::class, 'getJsonRole'])->name('getjsonrole');
    Route::get('role/{id}', [RoleController::class, 'detail'])->name('role.detail');
    Route::get('role/edit/{id}', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('role/edit/{id}', [RoleController::class, 'update'])->name('role.update');
    // setting
    Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('setting/store', [SettingController::class, 'store'])->name('setting.store');
    Route::put('setting/update/{id}', [SettingController::class, 'update'])->name('setting.update');
    // data karyawan
    Route::get('karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
    Route::post('getjsonkaryawan', [KaryawanController::class, 'getJsonKaryawan'])->name('getjsonkaryawan');
    Route::get('karyawan/trashed', [KaryawanController::class, 'trashed'])->name('karyawan.trashed');
    Route::post('getjsonkaryawantrashed', [KaryawanController::class, 'getJsonKaryawanTrashed'])->name('getjsonkaryawantrashed');
    Route::get('karyawan/{username}', [KaryawanController::class, 'detail'])->name('karyawan');
    Route::put('karyawan/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
    Route::get('karyawan/{username}/katasandi', [KaryawanController::class, 'update_password'])->name('karyawan.katasandi');
    Route::put('karyawan/password/{id}', [KaryawanController::class, 'password'])->name('karyawan.password');
    Route::get('karyawan/{id}/verifikasi', [KaryawanController::class, 'verifikasi'])->name('karyawan.verifikasi');
    Route::delete('karyawan/{id}/destroy', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
    Route::post('karyawan/{id}/restore', [KaryawanController::class, 'restore'])->name('karyawan.restore');
    // data pasang baru
    Route::get('data-pasang-baru', [DataPasangBaruController::class, 'index'])->name('data-pasang-baru.index');
    Route::post('data-pasang-baru/store', [DataPasangBaruController::class, 'store'])->name('data-pasang-baru.store');
    Route::post('getjsonpasangbaru', [DataPasangBaruController::class, 'getJsonPasangBaru'])->name('getjsonpasangbaru');
    Route::get('data-pasang-baru/{kode}', [DataPasangBaruController::class, 'detail'])->name('data-pasang-baru.detail');
    Route::get('data-pasang-baru/edit/{kode}', [DataPasangBaruController::class, 'edit'])->name('data-pasang-baru.edit');
    Route::put('data-pasang-baru/edit/{id}', [DataPasangBaruController::class, 'update'])->name('data-pasang-baru.update');
    Route::delete('data-pasang-baru/hapus/{id}', [DataPasangBaruController::class, 'delete'])->name('data-pasang-baru.hapus');
    // data job
    Route::get('data-job', [DataJobController::class, 'index'])->name('data-job.index');
    Route::post('data-job/store', [DataJobController::class, 'store'])->name('data-job.store');
    Route::post('getjsondatajob', [DataJobController::class, 'getJsonDataJob'])->name('getjsondatajob');
    Route::get('data-job/{id}', [DataJobController::class, 'detail'])->name('data-job.detail');
    Route::get('data-job/edit/{id}', [DataJobController::class, 'edit'])->name('data-job.edit');
    Route::put('data-job/edit/{id}', [DataJobController::class, 'update'])->name('data-job.update');
    Route::delete('data-job/hapus/{id}', [DataJobController::class, 'delete'])->name('data-job.hapus');
    // absen
    Route::post('absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');

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
