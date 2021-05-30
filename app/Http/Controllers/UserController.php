<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderMailing;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserController extends Controller
{
    public function index(Request $request){

    }

    //Приобритение тарифного плана. Не реализована сама покупка, т.к надо подключать сторонний сервис
    public function buySubscription(Request $request){
        $userId = $request->userid;
        $user = User::find($userId);
        if ($user === null)
            return response()->json('Пользователя не существует!', 404);
        // do magic (вызов обработчика платежей)
        Bouncer::allow('subscriber')->to('can-get-tenders');
        $user->assign('simple-user');

        return response()->json("Подписка куплена",200);
    }

    //Подписка на тендеры опредленного типа, только тому юзеру который приобрел тариф.
    public function subscribe(Request $request){
        $userId = $request->userid;
        $user = User::find($userId);
        if ($user === null)
            return response()->json('Пользователя не существует!', 404);
        $okvad2 = $request->okvad2;

        $tenderMailing = new TenderMailing();
        $tenderMailing->user = $user;
        $tenderMailing->okvad2_classifier = $okvad2;
        $tenderMailing->save();
        if ($tenderMailing===null){
            return response()->json('ты лох', 400);
        }
        return response()->json("Подписался",200);

    }

    public function unSubscribe(Request $request){

    }
}
