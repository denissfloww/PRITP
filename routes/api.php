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
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('/buy', 'App\Http\Controllers\UserController@buySubscription');
    Route::post('/unSubscribe', 'App\Http\Controllers\UserController@unSubscribe');
    Route::post('/subscribe', 'App\Http\Controllers\UserController@subscribe');
    Route::post('/addToFavorite', 'App\Http\Controllers\TenderController@addToFavorite');
    Route::post('/removeFromFavorite', 'App\Http\Controllers\TenderController@removeFromFavorite');
    Route::resource('tenders', TenderController::class);
});

Route::any('/login', 'App\Http\Controllers\AuthController@login')->name('login');

Route::get('/me', 'App\Http\Controllers\AuthController@me');
Route::get('/parse',function (\App\Services\XmlTenderParserService $parser) {
    $parser->parse();
});


