<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::get('test', [AuthController::class, 'gettest']);


    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify-user', [AuthController::class, 'verifyUser']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forget-password', [AuthController::class, 'forgetpassword']);
    Route::post('reset-password', [AuthController::class, 'resetpassword']);


    // protected routegroup
    Route::middleware('auth:sanctum')->group(function () {

        // auth route-group
        Route::prefix('auth')->group(function () {
            Route::post('change-password', [AuthController::class, 'changepassword']);
            Route::put('update-profile',[AuthController::class,'edit']);
            Route::post('logout', [AuthController::class, 'logout']);
        });



    });
});
