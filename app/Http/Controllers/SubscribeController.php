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

class SubscribeController extends Controller
{
    public function index(Request $request){

    }



    //Приобретение тарифного плана. Не реализована сама покупка, т.к надо подключать сторонний сервис
    /**
     * @OA\put(
     * path="/api/subscribe/buy",
     * summary="Покупка подписки",
     * description="пользователь покупает подписку",
     * operationId="buy",
     * tags={"subscribe"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\Response(
     *    response=404,
     *    description="Пользователя не существует",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Пользователя не существует")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Подписка куплена",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="string", example="Подписка куплена")
     *        )
     *     ),
     * )
     */
    public function buySubscription(Request $request) {
        $user= auth()->user();
        if ($user === null)
            return response()->json('Пользователя не существует', 404);


        // do magic (вызов обработчика платежей)


        Bouncer::allow('subscriber')->to('can-get-tenders');
        $user->assign('subscriber');
        return response()->json("Подписка куплена",200);
    }

    //Подписка на тендеры опредленного типа, только тому юзеру который приобрел тариф.
    /**
     * @OA\Post(
     * path="/api/tenders/mailing",
     * summary="Подписка на тендер",
     * description="Пользователь подписывается на изменения тендера",
     * operationId="mailing",
     * tags={"tender"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="передавать okvad2 тендера и JWT токен пользователя",
     *    @OA\JsonContent(
     *       required={"okvad2"},
     *       @OA\Property(property="okvad2", type="string", example="12.123"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="okvad2 не существует",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="okvad2 не существует")
     *        )
     *     ),
     * @OA\Response(
     *    response=403,
     *    description="У вас нет прав на подписку",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="У вас нет прав на подписку")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="У вас уже есть такая подписка",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="У вас уже есть такая подписка")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Подписался",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="string", example="Подписался")
     *        )
     *     ),
     * )
     */
    public function subscribe(Request $request){
        $user = auth()->user();
        $okvad2 = $request->okvad2;

        if ($user === null)
            return response()->json('Пользователя не существует', 404);
        if ($okvad2 === null)
            return response()->json('okvad2 не существует', 404);
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


    /**
     * @OA\delete(
     * path="/api/tenders/mailing",
     * summary="Отписка от тендера",
     * description="Пользователь отписывается от изменения тендера",
     * operationId="Unmailing",
     * tags={"tender"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="передавать okvad2 тендера и JWT токен пользователя",
     *    @OA\JsonContent(
     *       required={"okvad2"},
     *       @OA\Property(property="okvad2", type="string", example="12.123"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="okvad2 не существует",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="okvad2 не существует")
     *        )
     *     ),
     * @OA\Response(
     *    response=403,
     *    description="У вас нет прав на подписку",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="У вас нет прав на подписку")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Подписка куплена",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="string", example="Отписался")
     *        )
     *     ),
     * )
     */
    public function unSubscribe(Request $request){
        $user= auth()->user();
        $okvad2 = $request->okvad2;

        if ($user === null)
            return response()->json('Пользователя не существует', 422);
        if ($okvad2 === null)
            return response()->json('okvad2 не существует', 422);

        TenderMailing::where([
            ['user_id', '=',$user->id],
            ['okvad2_classifier','=',$okvad2]
        ])->delete();                                                                                                                                 //ларавель для дебилов

        return response()->json("Отписался",200);




    }


}
