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
    public function index()
    {
        return new Response(
            Player::query()
            ->select(['id', 'name','hp','mp','money'])
            ->get()
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new Response(
            Player::query()
                ->where('id', $id) // 指定したIDに一致するプレイヤーを検索
                ->select(['id', 'name', 'hp', 'mp', 'money']) // 指定したカラムを選択
                ->first() // 指定したIDを検索し、一番初めに合致した結果を取得
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Player::insertGetid(
            [
            'id'=>$request->id,
            'name'=>$request->name,
            'hp'=>$request->hp,
            'mp'=>$request->mp,
            'money'=>$request->money,
        ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Player::Where('id',$id)
        ->update(
            [
            'name'=>$request->name,
            'hp'=>$request->hp,
            'mp'=>$request->mp,
            'money'=>$request->money,
        ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Player::where('id', $id) // 指定したIDに一致するプレイヤーを検索
        ->delete();
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