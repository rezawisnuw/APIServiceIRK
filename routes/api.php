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


//-----------------------START OLD SCHEME-----------------------------------------
// Route::group(['middleware' => ['cors']], function() {

//     //START DEV
//     //IRK
//     // Route::group(['prefix' => 'dev'], function () {
//     //     Route::post('auth',[Dev\AuthController::class, 'Authentication']);

//     //     //Ceritakita
//     //     Route::post('ceritakita/get',[Dev\IRK\CeritakitaController::class, 'get']);
//     //     Route::post('ceritakita/post',[Dev\IRK\CeritakitaController::class, 'post']);
//     //     Route::post('ceritakita/put',[Dev\IRK\CeritakitaController::class, 'put']);
//     //     Route::post('ceritakita/delete',[Dev\IRK\CeritakitaController::class, 'delete']);
//     //     //Motivasi
//     //     Route::post('motivasi/get',[Dev\IRK\MotivasiController::class, 'get']);
//     //     Route::post('motivasi/post',[Dev\IRK\MotivasiController::class, 'post']);
//     //     Route::post('motivasi/put',[Dev\IRK\MotivasiController::class, 'put']);
//     //     Route::post('motivasi/delete',[Dev\IRK\MotivasiController::class, 'delete']);
//     //     //Curhatku
//     //     Route::post('curhatku/get',[Dev\IRK\CurhatkuController::class, 'get']);
//     //     Route::post('curhatku/post',[Dev\IRK\CurhatkuController::class, 'post']);
//     //     Route::post('curhatku/put',[Dev\IRK\CurhatkuController::class, 'put']);
//     //     Route::post('curhatku/delete',[Dev\IRK\CurhatkuController::class, 'delete']);
//     //     //Comment
//     //     Route::post('comment/get',[Dev\IRK\CommentController::class, 'get']);
//     //     Route::post('comment/post',[Dev\IRK\CommentController::class, 'post']);
//     //     Route::post('comment/put',[Dev\IRK\CommentController::class, 'put']);
//     //     Route::post('comment/delete',[Dev\IRK\CommentController::class, 'delete']);
//     //     //Like
//     //     Route::post('like/get',[Dev\IRK\LikeController::class, 'get']);
//     //     Route::post('like/post',[Dev\IRK\LikeController::class, 'post']);
//     //     Route::post('like/put',[Dev\IRK\LikeController::class, 'put']);
//     //     Route::post('like/delete',[Dev\IRK\LikeController::class, 'delete']);
//     //     //Report
//     //     Route::post('report/get',[Dev\IRK\ReportController::class, 'get']);
//     //     Route::post('report/post',[Dev\IRK\ReportController::class, 'post']);
//     //     Route::post('report/put',[Dev\IRK\ReportController::class, 'put']);
//     //     Route::post('report/delete',[Dev\IRK\ReportController::class, 'delete']);
//     //     //Profile
//     //     Route::post('profile/get',[Dev\IRK\ProfileController::class, 'get']);
//     //     Route::post('profile/post',[Dev\IRK\ProfileController::class, 'post']);
//     //     Route::post('profile/put',[Dev\IRK\ProfileController::class, 'put']);
//     //     Route::post('profile/delete',[Dev\IRK\ProfileController::class, 'delete']);
//     //     //Version
//     //     Route::post('version/get',[Dev\IRK\VersionController::class, 'get']);
//     //     Route::post('version/post',[Dev\IRK\VersionController::class, 'post']);
//     //     Route::post('version/put',[Dev\IRK\VersionController::class, 'put']);
//     //     Route::post('version/delete',[Dev\IRK\VersionController::class, 'delete']);
//     // });
//     //END DEV

//     //START STAG
//     //IRK
//     // Route::group(['prefix' => 'stag'], function () {
//     //     Route::post('auth',[Stag\AuthController::class, 'Authentication']);

//     //     //Ceritakita
//     //     Route::post('ceritakita/get',[Stag\IRK\CeritakitaController::class, 'get']);
//     //     Route::post('ceritakita/post',[Stag\IRK\CeritakitaController::class, 'post']);
//     //     Route::post('ceritakita/put',[Stag\IRK\CeritakitaController::class, 'put']);
//     //     Route::post('ceritakita/delete',[Stag\IRK\CeritakitaController::class, 'delete']);
//     //     //Motivasi
//     //     Route::post('motivasi/get',[Stag\IRK\MotivasiController::class, 'get']);
//     //     Route::post('motivasi/post',[Stag\IRK\MotivasiController::class, 'post']);
//     //     Route::post('motivasi/put',[Stag\IRK\MotivasiController::class, 'put']);
//     //     Route::post('motivasi/delete',[Stag\IRK\MotivasiController::class, 'delete']);
//     //     //Curhatku
//     //     Route::post('curhatku/get',[Stag\IRK\CurhatkuController::class, 'get']);
//     //     Route::post('curhatku/post',[Stag\IRK\CurhatkuController::class, 'post']);
//     //     Route::post('curhatku/put',[Stag\IRK\CurhatkuController::class, 'put']);
//     //     Route::post('curhatku/delete',[Stag\IRK\CurhatkuController::class, 'delete']);
//     //     //Comment
//     //     Route::post('comment/get',[Stag\IRK\CommentController::class, 'get']);
//     //     Route::post('comment/post',[Stag\IRK\CommentController::class, 'post']);
//     //     Route::post('comment/put',[Stag\IRK\CommentController::class, 'put']);
//     //     Route::post('comment/delete',[Stag\IRK\CommentController::class, 'delete']);
//     //     //Like
//     //     Route::post('like/get',[Stag\IRK\LikeController::class, 'get']);
//     //     Route::post('like/post',[Stag\IRK\LikeController::class, 'post']);
//     //     Route::post('like/put',[Stag\IRK\LikeController::class, 'put']);
//     //     Route::post('like/delete',[Stag\IRK\LikeController::class, 'delete']);
//     //     //Report
//     //     Route::post('report/get',[Stag\IRK\ReportController::class, 'get']);
//     //     Route::post('report/post',[Stag\IRK\ReportController::class, 'post']);
//     //     Route::post('report/put',[Stag\IRK\ReportController::class, 'put']);
//     //     Route::post('report/delete',[Stag\IRK\ReportController::class, 'delete']);
//     //     //Profile
//     //     Route::post('profile/get',[Stag\IRK\ProfileController::class, 'get']);
//     //     Route::post('profile/post',[Stag\IRK\ProfileController::class, 'post']);
//     //     Route::post('profile/put',[Stag\IRK\ProfileController::class, 'put']);
//     //     Route::post('profile/delete',[Stag\IRK\ProfileController::class, 'delete']);
//     //     //Version
//     //     Route::post('version/get',[Stag\IRK\VersionController::class, 'get']);
//     //     Route::post('version/post',[Stag\IRK\VersionController::class, 'post']);
//     //     Route::post('version/put',[Stag\IRK\VersionController::class, 'put']);
//     //     Route::post('version/delete',[Stag\IRK\VersionController::class, 'delete']);
//     // });
//     //END STAG

//     //START LIVE
//     //IRK
//     // Route::group(['prefix' => 'live'], function () {
//     //     Route::post('auth',[Live\AuthController::class, 'Authentication']);

//     //     //Ceritakita
//     //     Route::post('ceritakita/get',[Live\IRK\CeritakitaController::class, 'get']);
//     //     Route::post('ceritakita/post',[Live\IRK\CeritakitaController::class, 'post']);
//     //     Route::post('ceritakita/put',[Live\IRK\CeritakitaController::class, 'put']);
//     //     Route::post('ceritakita/delete',[Live\IRK\CeritakitaController::class, 'delete']);
//     //     //Motivasi
//     //     Route::post('motivasi/get',[Live\IRK\MotivasiController::class, 'get']);
//     //     Route::post('motivasi/post',[Live\IRK\MotivasiController::class, 'post']);
//     //     Route::post('motivasi/put',[Live\IRK\MotivasiController::class, 'put']);
//     //     Route::post('motivasi/delete',[Live\IRK\MotivasiController::class, 'delete']);
//     //     //Curhatku
//     //     Route::post('curhatku/get',[Live\IRK\CurhatkuController::class, 'get']);
//     //     Route::post('curhatku/post',[Live\IRK\CurhatkuController::class, 'post']);
//     //     Route::post('curhatku/put',[Live\IRK\CurhatkuController::class, 'put']);
//     //     Route::post('curhatku/delete',[Live\IRK\CurhatkuController::class, 'delete']);
//     //     //Comment
//     //     Route::post('comment/get',[Live\IRK\CommentController::class, 'get']);
//     //     Route::post('comment/post',[Live\IRK\CommentController::class, 'post']);
//     //     Route::post('comment/put',[Live\IRK\CommentController::class, 'put']);
//     //     Route::post('comment/delete',[Live\IRK\CommentController::class, 'delete']);
//     //     //Like
//     //     Route::post('like/get',[Live\IRK\LikeController::class, 'get']);
//     //     Route::post('like/post',[Live\IRK\LikeController::class, 'post']);
//     //     Route::post('like/put',[Live\IRK\LikeController::class, 'put']);
//     //     Route::post('like/delete',[Live\IRK\LikeController::class, 'delete']);
//     //     //Report
//     //     Route::post('report/get',[Live\IRK\ReportController::class, 'get']);
//     //     Route::post('report/post',[Live\IRK\ReportController::class, 'post']);
//     //     Route::post('report/put',[Live\IRK\ReportController::class, 'put']);
//     //     Route::post('report/delete',[Live\IRK\ReportController::class, 'delete']);
//     //     //Profile
//     //     Route::post('profile/get',[Live\IRK\ProfileController::class, 'get']);
//     //     Route::post('profile/post',[Live\IRK\ProfileController::class, 'post']);
//     //     Route::post('profile/put',[Live\IRK\ProfileController::class, 'put']);
//     //     Route::post('profile/delete',[Live\IRK\ProfileController::class, 'delete']);
//     //     //Version
//     //     Route::post('version/get',[Live\IRK\VersionController::class, 'get']);
//     //     Route::post('version/post',[Live\IRK\VersionController::class, 'post']);
//     //     Route::post('version/put',[Live\IRK\VersionController::class, 'put']);
//     //     Route::post('version/delete',[Live\IRK\VersionController::class, 'delete']);
//     // });
//     //END LIVE

// });
//-----------------------END OLD SCHEME-------------------------------------------


//-----------------------START NEW SCHEME-----------------------------------------
//IRK Endpoint
// Route::group([
//     'prefix' => '{slug}',
//     'where' => [
//         'slug' => 'dev|stag|live'
//     ],
//     'middleware' => 'cors'
// ], function () {

//     //Ceritakita
//     Route::post('ceritakita/get', [IRK\CeritakitaController::class, 'get']);
//     Route::post('ceritakita/post', [IRK\CeritakitaController::class, 'post']);
//     Route::post('ceritakita/put', [IRK\CeritakitaController::class, 'put']);
//     Route::post('ceritakita/delete', [IRK\CeritakitaController::class, 'delete']);
//     //Curhatku
//     Route::post('curhatku/get', [IRK\CurhatkuController::class, 'get']);
//     Route::post('curhatku/post', [IRK\CurhatkuController::class, 'post']);
//     Route::post('curhatku/put', [IRK\CurhatkuController::class, 'put']);
//     Route::post('curhatku/delete', [IRK\CurhatkuController::class, 'delete']);
//     //Motivasi
//     Route::post('motivasi/get', [IRK\MotivasiController::class, 'get']);
//     Route::post('motivasi/post', [IRK\MotivasiController::class, 'post']);
//     Route::post('motivasi/put', [IRK\MotivasiController::class, 'put']);
//     Route::post('motivasi/delete', [IRK\MotivasiController::class, 'delete']);
//     //Ideaku
//     Route::post('ideaku/get', [IRK\IdeakuController::class, 'get']);
//     Route::post('ideaku/post', [IRK\IdeakuController::class, 'post']);
//     Route::post('ideaku/put', [IRK\IdeakuController::class, 'put']);
//     Route::post('ideaku/delete', [IRK\IdeakuController::class, 'delete']);
//     //Comment
//     Route::post('comment/get', [IRK\CommentController::class, 'get']);
//     Route::post('comment/post', [IRK\CommentController::class, 'post']);
//     Route::post('comment/put', [IRK\CommentController::class, 'put']);
//     Route::post('comment/delete', [IRK\CommentController::class, 'delete']);
//     //Like
//     Route::post('like/get', [IRK\LikeController::class, 'get']);
//     Route::post('like/post', [IRK\LikeController::class, 'post']);
//     Route::post('like/put', [IRK\LikeController::class, 'put']);
//     Route::post('like/delete', [IRK\LikeController::class, 'delete']);
//     //Report
//     Route::post('report/get', [IRK\ReportController::class, 'get']);
//     Route::post('report/post', [IRK\ReportController::class, 'post']);
//     Route::post('report/put', [IRK\ReportController::class, 'put']);
//     Route::post('report/delete', [IRK\ReportController::class, 'delete']);
//     //Profile
//     Route::post('profile/get', [IRK\ProfileController::class, 'get']);
//     Route::post('profile/post', [IRK\ProfileController::class, 'post']);
//     Route::post('profile/put', [IRK\ProfileController::class, 'put']);
//     Route::post('profile/delete', [IRK\ProfileController::class, 'delete']);
//     //Version
//     Route::post('version/get', [IRK\VersionController::class, 'get']);
//     Route::post('version/post', [IRK\VersionController::class, 'post']);
//     Route::post('version/put', [IRK\VersionController::class, 'put']);
//     Route::post('version/delete', [IRK\VersionController::class, 'delete']);

// });
//-----------------------END NEW SCHEME-----------------------------------------




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