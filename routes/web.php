<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Middleware\VerifikasiAkun;

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

// Route::get('pwd',function(){
// 	dd(bcrypt('123'));
// });

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['guest'])->group(function () {
    Route::get('registration', [RegisterController::class, 'index'])->name('registration');
    Route::post('registration', [RegisterController::class, 'store'])->name('registration.store');
});

Route::middleware(['auth',VerifikasiAkun::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('setting/store', [SettingController::class, 'store'])->name('setting.store');
    Route::put('setting/update/{id}', [SettingController::class, 'update'])->name('setting.update');
    // profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile/update_password', [ProfileController::class, 'update_password'])->name('profile.update_password');
    Route::put('profile/password/{id}', [ProfileController::class, 'password'])->name('profile.password');

    Route::middleware([Admin::class])->group(function(){
        Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
        Route::post('setting/store', [SettingController::class, 'store'])->name('setting.store');
        Route::put('setting/update/{id}', [SettingController::class, 'update'])->name('setting.update');
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
