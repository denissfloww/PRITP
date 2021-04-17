<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::get('/me', 'App\Http\Controllers\AuthController@me');
Route::get('/parse',function (\App\Services\XmlTenderParserService $parser) {
        $parser->parse();


//    $rss = 'https://zakupki.gov.ru/epz/order/extendedsearch/rss.html?morphology=on&pageNumber=1&sortDirection=false&recordsPerPage=_10&showLotsInfoHidden=false&sortBy=UPDATE_DATE&fz44=on&fz223=on&af=on&ca=on&pc=on&pa=on&priceContractAdvantages44IdNameHidden=%7B%7D&priceContractAdvantages94IdNameHidden=%7B%7D&currencyIdGeneral=-1&selectedSubjectsIdNameHidden=%7B%7D&OrderPlacementSmallBusinessSubject=on&OrderPlacementRnpData=on&OrderPlacementExecutionRequirement=on&orderPlacement94_0=0&orderPlacement94_1=0&orderPlacement94_2=0&contractPriceCurrencyId=-1&budgetLevelIdNameHidden=%7B%7D&nonBudgetTypesIdNameHidden=%7B%7D';
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $rss);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $output = curl_exec($ch);
//    curl_close($ch);
//
//    dd($output);
//
//    $xml = XmlParser::load($xml);
//
//
//    echo "<ul>";
//    foreach($xml->channel->item as $item) {
//        echo "<li><a href = '{$item->link}' title = '$item->title'>",
//        htmlspecialchars($item->title), "</a> - ", $item->description, "</li>";
//    }
//    echo "</ul>";
////    $xml = XmlParser::load($output);
////    dump($xml);
//    die(get_class($parser));
});
