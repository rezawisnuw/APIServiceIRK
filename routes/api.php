<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dev;
use App\Http\Controllers\Stag;
use App\Http\Controllers\Live;
use App\Http\Controllers\IRK;

/*
|--------------------------------------------------------------------------
| API Routes Guidance
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

//-----------------------START SCHEME VERSE-----------------------------------------
//IRK Endpoint
Route::group([
    'prefix' => 'v{x}/{slug}',
    'where' => [
        'slug' => 'dev|stag|live',
        'x' => '[1-9]+'
    ],
    'middleware' => 'cors'
], function () {

    //Version Endpoint
    Route::group(['prefix' => 'version'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\VersionController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\VersionController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\VersionController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\VersionController")->delete(request());
        });
    });

    //Ceritakita Endpoint
    Route::group(['prefix' => 'ceritakita'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CeritakitaController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CeritakitaController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CeritakitaController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CeritakitaController")->delete(request());
        });
    });

    //Curhatku Endpoint
    Route::group(['prefix' => 'curhatku'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CurhatkuController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CurhatkuController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CurhatkuController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CurhatkuController")->delete(request());
        });
    });

    //Motivasi Endpoint
    Route::group(['prefix' => 'motivasi'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\MotivasiController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\MotivasiController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\MotivasiController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\MotivasiController")->delete(request());
        });
    });

    //Ideaku Endpoint
    Route::group(['prefix' => 'ideaku'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\IdeakuController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\IdeakuController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\IdeakuController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\IdeakuController")->delete(request());
        });
    });

    //Ceritaku Endpoint
    Route::group(['prefix' => 'ceritaku'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CeritakuController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CeritakuController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CeritakuController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CeritakuController")->delete(request());
        });
    });

    //Comment Endpoint
    Route::group(['prefix' => 'comment'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CommentController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CommentController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CommentController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\CommentController")->delete(request());
        });
    });

    //Like Endpoint
    Route::group(['prefix' => 'like'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\LikeController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\LikeController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\LikeController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\LikeController")->delete(request());
        });
    });

    //Report Endpoint
    Route::group(['prefix' => 'report'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\ReportController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\ReportController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\ReportController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\ReportController")->delete(request());
        });
    });

    //Profile Endpoint
    Route::group(['prefix' => 'profile'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\ProfileController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\ProfileController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\ProfileController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\ProfileController")->delete(request());
        });
    });

    //Faq Endpoint
    Route::group(['prefix' => 'faq'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\FaqController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\FaqController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\FaqController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\FaqController")->delete(request());
        });
    });

    //Filemanager Endpoint
    Route::group(['prefix' => 'filemanager'], function () {
        Route::post('get', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\FilemanagerController")->get(request());
        });
        Route::post('post', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\FilemanagerController")->post(request());
        });
        Route::post('put', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\FilemanagerController")->put(request());
        });
        Route::post('delete', function ($x) {
            return app("App\\Http\\Controllers\\IRK_v{$x}\\FilemanagerController")->delete(request());
        });
    });
});
//-----------------------END SCHEME VERSE-----------------------------------------