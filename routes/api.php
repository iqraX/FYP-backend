<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;

use App\Http\Controllers\HospitalController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\NumberController;



use App\Models\User;

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



Route::post('user-signup', [UserController::class, 'userSignUp']);
Route::post('user-login', [UserController::class, 'userLogin']);
Route::get('user/{id}', [UserController::class, 'userDetail']);
Route::post('user-details', [UserController::class, 'getDetail']);
Route::post('edit-details', [UserController::class, 'editDetail']);
Route::post('delete', [UserController::class, 'deleteUser']);
Route::post('changepassword', [UserController::class, 'changePassword']);

Route::post('location', [DriverController::class, 'savelocation']);
Route::post('online', [DriverController::class, 'online_status']);
Route::post('getdrivers', [DriverController::class, 'getdrivers']);
Route::post('check', [DriverController::class, 'check']);

Route::post('gethospitals', [HospitalController::class, 'getHospitals']);
Route::post('personalize',[HospitalController::class,'personalize']);

Route::post('postrequest', [RideController::class, 'postrequest']);
Route::post('checkrequest', [RideController::class, 'checkrequest']);
Route::post('confirm', [RideController::class, 'confirmride']);

Route::post('save_code', [NumberController::class, 'save_code']);

