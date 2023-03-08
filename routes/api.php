<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dev;
use App\Http\Controllers\Stag;
use App\Http\Controllers\Live;

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
    Route::post('auth/Dev',[Dev\AuthController::class, 'Authentication']);
    //STAG
    Route::post('auth/Stag',[Stag\AuthController::class, 'Authentication']);
    //LIVE
    Route::post('auth',[Live\AuthController::class, 'Authentication']);
});
