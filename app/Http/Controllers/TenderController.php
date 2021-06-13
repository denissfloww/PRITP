<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderMailing;
use App\Models\User;
use App\Services\ExcelExporter\ExportService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Silber\Bouncer\BouncerFacade as Bouncer;

class TenderController extends Controller
{
    /**
     * @OA\get (
     * path="/api/tenders",
     * summary="Возращает тендеры",
     * description="возращает тендеры с возможностью фильрации",
     * operationId="tenders",
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
     *   @OA\Parameter(
     *         name="number",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *  @OA\Parameter(
     *         name="name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *   @OA\Parameter(
     *         name="description",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="source_url",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="start_requset_date",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *             format="timestamp"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="end_requset_date",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *             format="timestamp"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="result_date",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *             format="timestamp"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="nmc_price",
     *         in="query",
     *         @OA\Schema(
     *             type="float",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="ensure_request_price",
     *         in="query",
     *         @OA\Schema(
     *             type="float",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="ensure_contract_price",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="currency_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="stage_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="okvad2_classifier",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_inn",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_ogrn",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_kpp",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_location",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_contact_phone",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_contact_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="currency",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="currency_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="stage_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="objects_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="objects_okvad",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),

     * @OA\Response(
     *    response=404,
     *    description="Тендеры не найдены",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Тендеров соответствующих таким фильтрам нет")
     *        )
     *     ),
     * @OA\Response(
     *    response=403,
     *    description="Пользователь не зарегистрирован",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Пользователь не зарегистрирован")
     *        )
     *     ),
     *     @OA\Response(
     *    response=200,
     *    description="Возвращенные тендеры",
     *    @OA\JsonContent(
     * @OA\Property(property="Tender", type="object", ref="#/components/schemas/Tender"),
     *        )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user === null){
            return response()->json('Пользователя не существует!', 404);
        }

        if (Bouncer::is($user)->an('simple-user')){
            $tenders=Tender::filter($request->all())->get();
            if ($tenders==null)
                return response()->json('Тендеров соответствующих таким фильтрам нет', 403);
            return response()->json(Tender::filter($request->all())->get(),200);
        }

        return response()->json('Пользователь не зарегистрирован', 403);

    }


    /**
     * @OA\Post(
     * path="/api/tenders/favorite",
     * summary="Добавление в избранное",
     * description="Добавляет тендер в избранное по id",
     * operationId="addToFavorite",
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
     *    description="передавать id тендера и JWT токен пользователя",
     *    @OA\JsonContent(
     *       required={"tender_id"},
     *       @OA\Property(property="tender_id", type="string", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Такого тендера не существует",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Такого тендера не существует")
     *        )
     *     ),
     * @OA\Response(
     *    response=403,
     *    description="Отсутствуют права на добавление в избранное",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Отсутствуют права на добавление в избранное")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="У вас уже есть тендер в избранном",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="У вас уже есть тендер в избранном")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Добавлен в избранное",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="string", example="Добавлен в избранное")
     *        )
     *     ),
     * )
     */
    public function addToFavorite(Request $request) {
        $user = auth()->user();
        $tenderId = $request->tender_id;
        $tender = Tender::find($tenderId);

        if ($user === null)
            return response()->json('Пользователя не существует', 404);
        if ($tender === null)
            return response()->json('Такого тендера не существует', 404);

        if (!Bouncer::is($user)->an('subscriber'))
            return response()->json('Отсутствуют права на добавление в избранное', 403);


        $favoriteTender = $user->favoriteTenders()->find($tenderId);
        if ($favoriteTender !== null)
            return response()->json("У вас уже есть тендер в избранном", 400);


        $user->favoriteTenders()->attach($tenderId);
        return response()->json("Добавлен в избранное",200);
    }

    /**
     * @OA\delete(
     * path="/api/tenders/favorite",
     * summary="Удаление из избранного",
     * description="Удаляет тендер из избранного по id",
     * operationId="removeFromFavorite",
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
     *    description="передавать id тендера и JWT токен пользователя",
     *    @OA\JsonContent(
     *       required={"tender_id"},
     *       @OA\Property(property="tender_id", type="string", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Такого тендера не существует",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Такого тендера не существует")
     *        )
     *     ),
     * @OA\Response(
     *    response=403,
     *    description="Отсутствуют права на удаление из избранного",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Отсутствуют права на удаление из избранного")
     *        )
     *     ),
     * @OA\Response(
     *    response=400,
     *    description="У вас уже есть тендер в избранном",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="У вас уже есть тендер в избранном")
     *        )
     *     ),
     * @OA\Response(
     *    response=201,
     *    description="Удалён из избранного",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Удалён из избранного")
     *        )
     *     )
     * )
     */
    public function removeFromFavorite(Request $request){
        $user = auth()->user();
        $tenderId = $request->tender_id;
        $tender = Tender::find($tenderId);


        if ($user === null)
            return response()->json('Пользователя не существует!', 404);
        if ($tender === null)
            return response()->json('Такого тендера не существует!', 404);

        if (!Bouncer::is($user)->an('subscriber'))
            return response()->json('Отсутствуют права на удаление из избранного', 403);


        $favoriteTender = $user->favoriteTenders()->find($tenderId);
        if ($favoriteTender === null)
            return response()->json("У вас нет тендера в избранном", 400);


        $user->favoriteTenders()->detach($tenderId);
        return response()->json("Удалён из избранного",201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tender  $tender
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Tender $tender)
    {
        return response()->json($tender, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tender  $tender
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Tender $tender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tender  $tender
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tender $tender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tender  $tender
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tender $tender)
    {
        //
    }

    /**
     * @OA\get(
     * path="/api/tenders/export",
     * summary="Экспорт",
     * description="Экопртирует тендоры по фильтрам в excel",
     * operationId="export",
     * tags={"tender"},
     * security={ {"bearer": {} }},
     *     @OA\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="currency_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="stage_id",
     *         in="query",
     *         @OA\Schema(
     *             type="integer",
     *             format="int32",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="okvad2_classifier",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_inn",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_ogrn",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_kpp",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_location",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_contact_phone",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="customer_contact_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="type_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="currency",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="currency_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="stage_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="objects_name",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="objects_okvad",
     *         in="query",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     * @OA\Response(
     *    response=403,
     *    description="Отсутствуют права на выгрузку в excel",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Отсутствуют права на выгрузку в excel")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Данные выгружены в excel",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Удалён из избранного")
     *        )
     *     ),
     * )
     */
    public function exportExcel(Request $request){
        $user = auth()->user();
        if (Bouncer::is($user)->an('subscriber')){
            $tenders = Tender::filter($request->all())->get();
            return Excel::download(new ExportService($tenders), 'Тендеры.xlsx');
        }
        return response()->json('Отсутствуют права на выгрузку в excel', 403);

    }

    //TODO: Доку тут
    public function getUserFavouriteTenders(Request $request){
        $user = auth()->user();
        $userFavouriteTenders =  $user->favoriteTenders()->get();

        return response()->json($userFavouriteTenders, 200);
    }

}
