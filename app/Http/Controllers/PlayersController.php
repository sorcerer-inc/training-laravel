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
                ->where('id','=',$id) // 指定したIDに一致するプレイヤーを検索
                ->select(['id', 'name', 'hp', 'mp', 'money']) // 指定したカラムを選択
                ->get() // 結果を取得
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

        Player::insertGetid(    // 送られてきたデータに沿ってカラムを新規作成
            [
            'id'=>$request->id,
            'name'=>$request->name,
            'hp'=>$request->hp,
            'mp'=>$request->mp,
            'money'=>$request->money,
        ]
        );

        // JSONレスポンスとしてidを返す
        return response()->json(['id' => $request->id]);
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
        Player::Where('id','=',$id) // 指定したIDに一致するプレイヤーを検索
        ->update(   // 送られてきたデータに沿って指定されたIDのカラムを更新
            [
            'name'=>$request->name,
            'hp'=>$request->hp,
            'mp'=>$request->mp,
            'money'=>$request->money,
        ]
        );

        // UP DATEが完了したらJSONレスポンスを返す
        return response()->json(['update complete.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Player::where('id','=',$id) // 指定したIDに一致するプレイヤーを検索
        ->delete(); // 削除

        // DELETEが完了したらJSONレスポンスを返す
        return response()->json(['delete complete.']);
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