<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //全てのプレイヤーテーブルのカラムの表示
    public function index()
    {
        return new Response(
            Player::query()->
            select(['id', 'name','hp','mp','money'])->
            get());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //指定したIDの情報をプレイヤーテーブルから表示
    public function show($id)
    {
        return new Response(
            Player::query()->
            find($id)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //新規のIDを自動で割り当て入力した情報を挿入する
    public function store(Request $request)
    {
       
        $id = Player::query()->
        insertGetId([
            'name'  => $request->name,
            'hp'    => $request->hp,
            'mp'    => $request->mp,
            'money' => $request->money,
        ]);

        return Response() -> json(['id' => $id]);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //指定したIDに入力した情報を更新する
    public function update(Request $request, $id)
    {
        Player::query()->
        where('id',$id)->
        update(
            $request->all()
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //指定したIDの情報を削除する
    public function destroy($id)
    {
        Player::query()->
        where('id',$id)->
        delete();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
}
