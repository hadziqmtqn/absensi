<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
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
    Route::post('login', [AuthController::class, 'login']);
    // auth
    Route::middleware('api.key')->group(function () {
        Route::get('user', [UserController::class, 'index']);

        Route::post('logout', [AuthController::class, 'logout']);
    });
});
