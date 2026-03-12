<?php

use App\Http\Controllers\Api\CatrgoriesController;
use App\Http\Controllers\Api\TasksController;
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
    Route::post('forget-password', [AuthController::class, 'forgetPassword']);
    Route::post('reset-password', [AuthController::class, 'resetpassword']);



    // protected routegroup
    Route::middleware('auth:sanctum')->group(function () {
        // Route::middleware('admin')->group(function () {
            //    return response()->json([
            //         'success' => true,
            //         'message' => 'Welcome to the admin dashboard'
            //     ]); });

            
            Route::prefix('category')->middleware('admin')->group(function () {
                Route::get('get-all', [CatrgoriesController::class, 'getall']);
                Route::post('create', [CatrgoriesController::class, 'createCategory']);
                Route::put('update/{id}', [CatrgoriesController::class, 'updateCategory']);
                Route::post('delete/{id}', [CatrgoriesController::class, 'deleteCategory']);
            });
            Route::prefix('task')->group(function(){
                Route::get('getall',[TasksController::class,'getAll']);
                Route::post('create',[TasksController::class,'createCategory']);
                Route::put('update',[TasksController::class,'update']);

            });
       
        // auth route-group
        Route::prefix('auth')->group(function () {
            Route::post('change-password', [AuthController::class, 'changepassword']);
            Route::put('update-profile', [AuthController::class, 'edit']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });
});
