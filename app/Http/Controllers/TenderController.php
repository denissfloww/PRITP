<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use App\Models\TenderFavorite;
use App\Models\TenderMailing;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user === null){
            return response()->json('Пользователя не существует!', 404);
        }

        if (Bouncer::is($user)->an('simple-user')){
            return response()->json(Tender::filter($request->all())->get(),200);
        }

        return response()->json('Отсутствуют права на поиск', 403);;

    }

    public function addToFavorite(Request $request) {
        $user = auth()->user();
        $tenderId = $request->tender_id;
        $tender = Tender::find($tenderId);


        if ($user === null)
            return response()->json('Пользователя не существует!', 404);
        if ($tender === null)
            return response()->json('Такого тендера не существует!', 404);

        if (!Bouncer::is($user)->an('subscriber'))
            return response()->json('Отсутствуют права на поиск', 403);


        $favoriteTender = $user->favoriteTenders()->find($tenderId);
        if ($favoriteTender !== null)
            return response()->json("У вас уже есть тендер в избранном", 400);


        $user->favoriteTenders()->attach($tenderId);
        return response()->json("Добавлен в избранное",200);
    }


    public function removeFromFavorite(Request $request){
        $user = auth()->user();
        $tenderId = $request->tender_id;
        $tender = Tender::find($tenderId);


        if ($user === null)
            return response()->json('Пользователя не существует!', 404);
        if ($tender === null)
            return response()->json('Такого тендера не существует!', 404);

        if (!Bouncer::is($user)->an('subscriber'))
            return response()->json('Отсутствуют права на поиск', 403);


        $favoriteTender = $user->favoriteTenders()->find($tenderId);
        if ($favoriteTender === null)
            return response()->json("У вас нет тендера в избранном", 400);


        $user->favoriteTenders()->detach($tenderId);
        return response()->json("Удалён из избранного",200);
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
}
