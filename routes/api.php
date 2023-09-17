<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Mobile\AuthenticatController;
use App\Http\Controllers\Mobile\ForgotPasswordController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//routes admin
Route::post('admin/login',[AuthController::class, 'login_admin']);
Route::group(['middleware'=>['auth:admins'],'prefix'=>'admin'],function(){
    Route::post('logout',[AuthController::class, 'logout']);

    });


//routes user
Route::post('login',[AuthenticatController::class, 'login']);
Route::post('register',[AuthenticatController::class, 'register']);
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm']); 
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm']);


Route::group(['middleware'=>['auth:users']],function(){
    Route::post('logout',[AuthenticatController::class, 'logout']);
});