<?php

use App\Http\Controllers\API\ApiKeyController;
use App\Http\Controllers\API\AuthController;
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
        Route::delete('/{idapi}/delete', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/{idapi}/restore', [UserController::class, 'restore'])->name('user.restore');
        Route::delete('/{idapi}/delete-permanen', [UserController::class, 'deletePermanen'])->name('user.delete-permanen');
    });
});
