<?php

use App\Http\Controllers\API\AbsensiController;
use App\Http\Controllers\API\ApiKeyController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DataJobController;
use App\Http\Controllers\API\PasangBaruController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\TeknisiCadanganController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('log.route.api')->group(function (){
    // default login
    Route::get('login', [AuthController::class, 'home'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('login-api', [ApiKeyController::class, 'apiKeyLogin']);
    // get check api key
    Route::get('get-api-key', [ApiKeyController::class, 'index'])->name('get-api-key');
    Route::prefix('user')->group(function(){
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::put('/{idapi}/update', [UserController::class, 'update'])->name('user.update');
        Route::delete('/{idapi}/delete', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/{idapi}/restore', [UserController::class, 'restore'])->name('user.restore');
        Route::delete('/{idapi}/delete-permanen', [UserController::class, 'deletePermanen'])->name('user.delete-permanen');
        Route::put('/{idapi}/update-password', [UserController::class, 'updatePassword'])->name('user.update-password');
    });
    // update profile
    Route::put('profile/{idapi}/update', [ProfileController::class, 'update'])->name('profile.update');
    // absensi
    Route::prefix('absensi')->group(function(){
        Route::post('/{idapi}/store', [AbsensiController::class, 'store'])->name('absensi.store');
    });
    Route::post('data-pasang-baru', [PasangBaruController::class, 'store'])->name('data-pasang-baru.store');
    Route::post('data-job/{idapi}', [DataJobController::class, 'store'])->name('data-job.store');
    Route::delete('teknisi-cadangan/{idapi}/delete', [TeknisiCadanganController::class, 'delete'])->name('teknisi-cadangan.delete');
});
