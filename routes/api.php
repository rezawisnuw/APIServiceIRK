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
    //IRK
    Route::group(['prefix' => 'dev'], function () {
        Route::post('auth',[Dev\AuthController::class, 'Authentication']);

        //Ceritakita
        Route::post('ceritakita/get',[Dev\IRK\CeritakitaController::class, 'get']);
        Route::post('ceritakita/post',[Dev\IRK\CeritakitaController::class, 'post']);
        Route::post('ceritakita/put',[Dev\IRK\CeritakitaController::class, 'put']);
        Route::post('ceritakita/delete',[Dev\IRK\CeritakitaController::class, 'delete']);
        //Motivasi
        Route::post('motivasi/get',[Dev\IRK\MotivasiController::class, 'get']);
        Route::post('motivasi/post',[Dev\IRK\MotivasiController::class, 'post']);
        Route::post('motivasi/put',[Dev\IRK\MotivasiController::class, 'put']);
        Route::post('motivasi/delete',[Dev\IRK\MotivasiController::class, 'delete']);
        //Curhatku
        Route::post('curhatku/get',[Dev\IRK\CurhatkuController::class, 'get']);
        Route::post('curhatku/post',[Dev\IRK\CurhatkuController::class, 'post']);
        Route::post('curhatku/put',[Dev\IRK\CurhatkuController::class, 'put']);
        Route::post('curhatku/delete',[Dev\IRK\CurhatkuController::class, 'delete']);
        //Comment
        Route::post('comment/get',[Dev\IRK\CommentController::class, 'get']);
        Route::post('comment/post',[Dev\IRK\CommentController::class, 'post']);
        Route::post('comment/put',[Dev\IRK\CommentController::class, 'put']);
        Route::post('comment/delete',[Dev\IRK\CommentController::class, 'delete']);
        //Like
        Route::post('like/get',[Dev\IRK\LikeController::class, 'get']);
        Route::post('like/post',[Dev\IRK\LikeController::class, 'post']);
        Route::post('like/put',[Dev\IRK\LikeController::class, 'put']);
        Route::post('like/delete',[Dev\IRK\LikeController::class, 'delete']);
    });
    //END DEV

    //START STAG
    //IRK
    Route::group(['prefix' => 'stag'], function () {
        Route::post('auth',[Stag\AuthController::class, 'Authentication']);

        //Ceritakita
        Route::post('ceritakita/get',[Stag\IRK\CeritakitaController::class, 'get']);
        Route::post('ceritakita/post',[Stag\IRK\CeritakitaController::class, 'post']);
        Route::post('ceritakita/put',[Stag\IRK\CeritakitaController::class, 'put']);
        Route::post('ceritakita/delete',[Stag\IRK\CeritakitaController::class, 'delete']);
        //Motivasi
        Route::post('motivasi/get',[Stag\IRK\MotivasiController::class, 'get']);
        Route::post('motivasi/post',[Stag\IRK\MotivasiController::class, 'post']);
        Route::post('motivasi/put',[Stag\IRK\MotivasiController::class, 'put']);
        Route::post('motivasi/delete',[Stag\IRK\MotivasiController::class, 'delete']);
        //Curhatku
        Route::post('curhatku/get',[Stag\IRK\CurhatkuController::class, 'get']);
        Route::post('curhatku/post',[Stag\IRK\CurhatkuController::class, 'post']);
        Route::post('curhatku/put',[Stag\IRK\CurhatkuController::class, 'put']);
        Route::post('curhatku/delete',[Stag\IRK\CurhatkuController::class, 'delete']);
        //Comment
        Route::post('comment/get',[Stag\IRK\CommentController::class, 'get']);
        Route::post('comment/post',[Stag\IRK\CommentController::class, 'post']);
        Route::post('comment/put',[Stag\IRK\CommentController::class, 'put']);
        Route::post('comment/delete',[Stag\IRK\CommentController::class, 'delete']);
        //Like
        Route::post('like/get',[Stag\IRK\LikeController::class, 'get']);
        Route::post('like/post',[Stag\IRK\LikeController::class, 'post']);
        Route::post('like/put',[Stag\IRK\LikeController::class, 'put']);
        Route::post('like/delete',[Stag\IRK\LikeController::class, 'delete']);
    });
    //END STAG

    //START LIVE
    //IRK
    Route::group(['prefix' => 'live'], function () {
        Route::post('auth',[Live\AuthController::class, 'Authentication']);
        
        //Ceritakita
        Route::post('ceritakita/get',[Live\IRK\CeritakitaController::class, 'get']);
        Route::post('ceritakita/post',[Live\IRK\CeritakitaController::class, 'post']);
        Route::post('ceritakita/put',[Live\IRK\CeritakitaController::class, 'put']);
        Route::post('ceritakita/delete',[Live\IRK\CeritakitaController::class, 'delete']);
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
        //Comment
        Route::post('comment/get',[Live\IRK\CommentController::class, 'get']);
        Route::post('comment/post',[Live\IRK\CommentController::class, 'post']);
        Route::post('comment/put',[Live\IRK\CommentController::class, 'put']);
        Route::post('comment/delete',[Live\IRK\CommentController::class, 'delete']);
        //Like
        Route::post('like/get',[Live\IRK\LikeController::class, 'get']);
        Route::post('like/post',[Live\IRK\LikeController::class, 'post']);
        Route::post('like/put',[Live\IRK\LikeController::class, 'put']);
        Route::post('like/delete',[Live\IRK\LikeController::class, 'delete']);
    });
    //END LIVE

});
