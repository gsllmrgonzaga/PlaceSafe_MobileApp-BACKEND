<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\Api;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\VerificationController;

use App\Http\Controllers\MapController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\LocationsController;

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

//Final User
Route::post('/loginUser',[UserController::class,'loginUser']);
Route::post('/registerUser',[UserController::class,'register']);
Route::post('/forgotpasswordUser',[UserController::class,'forgotpasswordUser']);

Route::middleware('auth:api')->post('change-password',[UserController::class,'changepassword']);
Route::middleware('auth:api')->post('profileUser',[UserController::class,'updateProfileUser']);
Route::middleware('auth:api')->get('showUser',[UserController::class,'showUser']);

//notification
Route::middleware('auth:api')->get('subscribe',[SubscriberController::class,'subscribe']);

//send email code
Route::middleware('auth:api')->get('resendPin',[VerificationController::class,'resendPin']);

//verify the code
Route::middleware('auth:api')->post('verifyEmail',[VerificationController::class,'verifyEmail']);

//PATIENT CONTROLLER
Route::get('/getTotalActiveCases',[PatientsController::class,'getTotalActiveCases']);
Route::get('/getTotalRecoveredCases',[PatientsController::class,'getTotalRecoveredCases']);
Route::get('/getTotalDeathCases',[PatientsController::class,'getTotalDeathCases']);
Route::get('/getTotalConfirmedCases',[PatientsController::class,'getTotalConfirmedCases']);

//LOCATIONS CONTROLLER
Route::get('/getListFilterLeastCases',[LocationsController::class,'getListFilterLeastCases']);
Route::get('/getListFilterMostCases',[LocationsController::class,'getListFilterMostCases']);
Route::get('/getPlace',[LocationsController::class,'getPlace']);
Route::get('/getLatestDate',[LocationsController::class,'getLatestDate']);

//MAP CONTROLLER
Route::post('/searchPlace',[MapController::class,'searchPlace']);
Route::post('/searchPlaceInfoActive',[MapController::class,'searchPlaceInfoActive']);
Route::post('/searchPlaceInfoRecovered',[MapController::class,'searchPlaceInfoRecovered']);
Route::post('/searchPlaceInfoDead',[MapController::class,'searchPlaceInfoDead']);