<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\TenderObjectController;
use App\Http\Controllers\TenderStageController;
use App\Http\Controllers\TenderTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tender_types', TenderTypeController::class);
Route::resource('tender_stages', TenderStageController::class);
Route::resource('customers', CustomerController::class);
Route::resource('currencies', CurrencyController::class);
Route::resource('tender_objects', TenderObjectController::class);
Route::resource('tenders', TenderController::class);
