<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderMailing;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use PHPUnit\Exception;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function index(Request $request){

    }

    //Приобретение тарифного плана. Не реализована сама покупка, т.к надо подключать сторонний сервис
    public function buySubscription(Request $request) {
        $user= auth()->user();
        if ($user === null)
            return response()->json('Пользователя не существует!', 422);


        // do magic (вызов обработчика платежей)


        Bouncer::allow('subscriber')->to('can-get-tenders');
        $user->assign('subscriber');
        return response()->json("Подписка куплена",200);
    }

    //Подписка на тендеры опредленного типа, только тому юзеру который приобрел тариф.
    public function subscribe(Request $request){
        $user = auth()->user();
        $okvad2 = $request->okvad2;

        if ($user === null)
            return response()->json('Пользователя не существует!', 422);
        if ($okvad2 === null)
            return response()->json('okvad2 не существует!', 422);
        if (!Bouncer::is($user)->an('subscriber'))
            return response()->json("У вас нет прав на подписку", 403);
        $tenderMailing = TenderMailing::where([
            ['user_id', $user->id],
            ['okvad2_classifier', $okvad2]
        ])->first();
        if ($tenderMailing !== null)
            return response()->json("У вас уже есть такая подписка", 400);

        $tenderMailing = new TenderMailing;
        $tenderMailing->user_id = $user->id;
        $tenderMailing->okvad2_classifier = $okvad2;
        $tenderMailing->save();
        return response()->json("Подписался", 200);
    }

    public function unSubscribe(Request $request){
        $user= auth()->user();
        $okvad2 = $request->okvad2;

        if ($user === null)
            return response()->json('Пользователя не существует!', 422);
        if ($okvad2 === null)
            return response()->json('okvad2 не существует!', 422);

        TenderMailing::where([
            ['user_id', '=',$user->id],
            ['okvad2_classifier','=',$okvad2]
        ])->delete();                                                                                                                                 //ларавель для дебилов

        return response()->json("Отписался",200);




    }


}
