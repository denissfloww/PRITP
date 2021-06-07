<?php

use App\Http\Controllers\TenderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orchestra\Parser\Xml\Facade as XmlParser;
use App\Services;

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



Route::middleware('auth:api')->group(function() {
    Route::get('/tenders/export', 'App\Http\Controllers\TenderController@exportExcel');

    Route::get('/me', 'App\Http\Controllers\AuthController@me');
    Route::put('/subscribe/buy', 'App\Http\Controllers\SubscribeController@buySubscription');

    Route::delete('/tenders/mailing', 'App\Http\Controllers\SubscribeController@unSubscribe');
    Route::post('/tenders/mailing', 'App\Http\Controllers\SubscribeController@subscribe');

    Route::post('/tenders/favorite', 'App\Http\Controllers\TenderController@addToFavorite');
    Route::delete('/tenders/favorite', 'App\Http\Controllers\TenderController@removeFromFavorite');

    Route::resource('tenders', TenderController::class);
});

Route::any('/login', 'App\Http\Controllers\AuthController@login')->name('login');
Route::post('/registry','App\Http\Controllers\AuthController@registry');
