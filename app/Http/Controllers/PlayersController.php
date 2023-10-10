<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Models\Item;
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
    //playerテーブルの全てのカラムを表示する
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
    //playerテーブルから指定したIDのカラムを表示する
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
    //playerテーブルに自動増分したIDに新規でカラムを挿入する
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
    //playerテーブルに指定したIDのカラムを指定した内容に更新する
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
    //playerテーブルから指定したIDのカラムを削除する
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
