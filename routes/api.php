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

    //START DEV
    Route::post('auth/dev',[Dev\AuthController::class, 'Authentication']);
    //IRK
    Route::group(['prefix' => 'irk'], function () {
        //Motivasi
        Route::post('motivasi/get/dev',[Dev\IRK\MotivasiController::class, 'get']);
        Route::post('motivasi/post/dev',[Dev\IRK\MotivasiController::class, 'post']);
        Route::post('motivasi/put/dev',[Dev\IRK\MotivasiController::class, 'put']);
        Route::post('motivasi/delete/dev',[Dev\IRK\MotivasiController::class, 'delete']);
        //Curhatku
        Route::post('curhatku/get/dev',[Dev\IRK\CurhatkuController::class, 'get']);
        Route::post('curhatku/post/dev',[Dev\IRK\CurhatkuController::class, 'post']);
        Route::post('curhatku/put/dev',[Dev\IRK\CurhatkuController::class, 'put']);
        Route::post('curhatku/delete/dev',[Dev\IRK\CurhatkuController::class, 'delete']);
    });
    //END DEV

    //START STAG
    Route::post('auth/stag',[Stag\AuthController::class, 'Authentication']);
    //IRK
    Route::group(['prefix' => 'irk'], function () {
        //Motivasi
        Route::post('motivasi/get/stag',[Stag\IRK\MotivasiController::class, 'get']);
        Route::post('motivasi/post/stag',[Stag\IRK\MotivasiController::class, 'post']);
        Route::post('motivasi/put/stag',[Stag\IRK\MotivasiController::class, 'put']);
        Route::post('motivasi/delete/stag',[Stag\IRK\MotivasiController::class, 'delete']);
        //Curhatku
        Route::post('curhatku/get/stag',[Stag\IRK\CurhatkuController::class, 'get']);
        Route::post('curhatku/post/stag',[Stag\IRK\CurhatkuController::class, 'post']);
        Route::post('curhatku/put/stag',[Stag\IRK\CurhatkuController::class, 'put']);
        Route::post('curhatku/delete/stag',[Stag\IRK\CurhatkuController::class, 'delete']);
    });
    //END STAG

    //START LIVE
    Route::post('auth',[Live\AuthController::class, 'Authentication']);
    //IRK
    Route::group(['prefix' => 'irk'], function () {
        //Motivasi
        Route::post('motivasi/get',[Live\IRK\MotivasiController::class, 'get']);
        Route::post('motivasi/post',[Live\IRK\MotivasiController::class, 'post']);
        Route::post('motivasi/put',[Live\IRK\MotivasiController::class, 'put']);
        Route::post('motivasi/delete',[Live\IRK\MotivasiController::class, 'delete']);
        //Curhatku
        Route::post('curhatku/get',[Live\IRK\CurhatkuController::class, 'get']);
        Route::post('curhatku/post',[Live\IRK\CurhatkuController::class, 'post']);
        Route::post('curhatku/put',[Live\IRK\CurhatkuController::class, 'put']);
        Route::post('curhatku/delete',[Live\IRK\CurhatkuController::class, 'delete']);
    });
    //END LIVE

});
