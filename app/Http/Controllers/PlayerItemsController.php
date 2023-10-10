<?php

namespace App\Http\Controllers;

use App\Models\PlayerItems;
use App\Models\Player;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // 追加

class PlayerItemsController extends Controller
{
    /*
    public function addItem(Request $request, $id)
    {
        // プレイヤーIDに対応するレコードを取得
        $playerItem = PlayerItems::where('player_id', $id)
            ->where('item_id', $request->itemId)
            ->first();

        // レコードが存在するかどうかで処理を分岐
        if ($playerItem) 
        {
            // 既存のアイテムが存在する場合、countを加算
            $playerItem->count += $request->count;
        } 
        
        else 
        {
            // アイテムが存在しない場合、新しいレコードを挿入
            $playerItem = new PlayerItems([
                'player_id' => $id,
                'item_id' => $request->itemId,
                'count' => $request->count,
            ]);
        }

        // データベースに保存
        $playerItem->save();

        // レスポンスを返す
        //return response()->json([]);
        return response('更新した。');
    }
    */
    public function addItem(Request $request, $id)
{
    // プレイヤーIDとアイテムIDでレコードをデータベースから検索
    $playerItem = PlayerItems::where('player_id', $id)
        ->where('item_id', $request->itemId)
        ->first();

    if (!$playerItem) {
        // アイテムが存在しない場合、新しいレコードを挿入
        $playerItem = new PlayerItems([
            'player_id' => $id,
            'item_id' => $request->itemId,
            'count' => $request->count,
        ]);

        // データベースに保存
        $playerItem->save();

        // レスポンスを返す
        return response('新しいアイテムを挿入しました。');
    }

    // トランザクションの開始
    DB::transaction(function () use ($playerItem, $request) {
        // ロックの取得
        $playerItem->lockForUpdate()->get();

        // 既存のアイテムが存在する場合、countを加算
        $playerItem->count += $request->count;

        // データベースに保存
        $playerItem->save();
    });

    // レスポンスを返す
    return response('アイテムのカウントを更新しました。');
}

}