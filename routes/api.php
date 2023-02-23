<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['middleware' => ['cors']], function() {
    //DEV
    Route::post('auth/Dev','AuthController@Authentication');
    //STAG
    Route::post('auth/Stag','AuthController@Authentication');
    //LIVE
    Route::post('auth','AuthController@Authentication');
});
