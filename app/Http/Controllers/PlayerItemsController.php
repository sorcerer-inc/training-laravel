<?php

namespace App\Http\Controllers;

use App\Models\PlayerItems;
use App\Models\Player;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PlayerItemsController extends Controller
{
    //アイテムの所持処理
    public function addItem(Request $request, $id)
    {
        // プレイヤーIDとアイテムIDでレコードをデータベースから検索
        $playerItem = PlayerItems::where('player_id', $id)
            ->where('item_id', $request->itemId)
            ->lockForUpdate() // ここでロックを取得
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
            return Response() -> json(['itemId' => $request->itemId,
            'count' => $request->count]);
        }

        // 既存のアイテムが存在する場合、countを加算
        $playerItem->count += $request->count;

        // データベースに保存
        $playerItem->save();

        // レスポンスを返す
        return Response() -> json(['itemId' => $request->itemId,
            'count' => $playerItem->count]);
    }

    //アイテムの使用処理
    public function useItem(Request $request, $id)
    {
        // プレイヤーIDとアイテムIDでレコードをデータベースから検索
        $playerItem = PlayerItems::where('player_id', $id)
            ->where('item_id', $request->itemId)
            ->first();

        // アイテムの所持数がゼロ && アイテムが存在しない場合はエラーレスポンスを返す
        if (!$playerItem || $playerItem->count <= 0) {
            return response()->json(['error' => 'No items remaining'], 400);
        }

        // HPとMPの上限は200
        $maxHp = 200;
        $maxMp = 200;

        // プレイヤーのステータスを取得
        $player = Player::find($id);

        // アイテムごとの処理
        if ($request->itemId == 1) 
        { // HPかいふく薬
            // アイテムの値を取得
            $itemValue = Item::where('id', $request->itemId)->value('value');

            // HP増加処理
            if($player->hp < $maxHp)// HPが上限に達していない場合のみ処理
            {
                $newHp = min($maxHp, $player->hp + $itemValue);

                $player->hp = $newHp;
                $playerItem->count -= 1;
            }
        } 
        elseif ($request->itemId == 2) 
        { // MPかいふく薬
            // アイテムの値を取得
            $itemValue = Item::where('id', $request->itemId)->value('value');

            // MP増加処理
            if($player->mp < $maxMp)// MPが上限に達していない場合のみ処理
            {
                $newMp = min($maxMp, $player->mp + $itemValue);

                $player->mp = $newMp;
                $playerItem->count -= 1;
            }
        } 
        else
        {
            // 不明なアイテムの場合はエラーレスポンスを返す
            return response()->json(['error' => 'Unknown item'], 400);
        }

        // プレイヤーのステータスを保存
        $player->save();
        $playerItem->save();

        // レスポンスを返す
        return response()->json([
            'itemId' => $request->itemId,
            'count' => $playerItem->count,
            'player' => [
                'id' => $player->id,
                'hp' => $player->hp,
                'mp' => $player->mp,
            ],
        ]);
    }
    
    // ガチャの抽選処理を修正
    private function selectItemByProbability()
    {
        // 各アイテムのpercentを取得
        $items = Item::all(['id', 'percent'])->toArray();

        // ランダムな数値を生成
        $randomPercent = mt_rand(1, 100); // 1から100までの範囲でランダム

        $selectedItemId = null;
        $currentPercent = 0;

        foreach ($items as $item) {
            $currentPercent += $item['percent'];

            if ($randomPercent <= $currentPercent) {
                $selectedItemId = $item['id'];
                break;
            }
        }

        return $selectedItemId;
    }

    // プレイヤーのアイテムデータを取得する処理を修正
    private function getPlayerItemsData($player)
    {
        // プレイヤーがアイテムを持っていない場合は空のコレクションを返す
        $playerItems = $player->playerItems ?? collect();

        return $playerItems->map(function ($item) {
            return [
                'itemId' => $item->item_id,
                'count' => $item->count,
            ];
        })->values()->all();
    }

    // ガチャの利用処理を修正
    public function useGacha(Request $request, $id)
    {
        // プレイヤーの存在確認
        $player = Player::find($id);

        // 所持金の確認
        $gachaCount = $request->input('count');
        $gachaCost = 10;
        $totalCost = $gachaCount * $gachaCost;

        if (!$player || $player->money < $totalCost) {
            return response()->json(['error' => 'Not enough money to perform Gacha.'], 400);
        }

        // ガチャを引く
        $gachaResults = [];

        // アイテムごとの count を保存する変数
        $itemCounts = [];

        for ($i = 0; $i < $gachaCount; $i++) {
            // アイテムの抽選
            $selectedItemId = $this->selectItemByProbability();

            // ハズレの場合はスキップ
            if ($selectedItemId) {
                // アイテムの増加処理
                $playerItem = PlayerItems::where('player_id', $player->id)
                    ->where('item_id', $selectedItemId)
                    ->first();

                if ($playerItem) {
                    $playerItem->count += 1;
                    $playerItem->save();
                } else {
                    $player->items()->attach($selectedItemId, ['count' => 1]);
                }

                $gachaResults[] = [
                    'itemId' => $selectedItemId,
                    'count' => 1,
                ];

                // count を保存
                if (!isset($itemCounts[$selectedItemId])) {
                    $itemCounts[$selectedItemId] = 0;
                }
                $itemCounts[$selectedItemId]++;
            }
        }

        // 所持金の更新
        $player->money -= $totalCost;
        $player->save();

        // ガチャが発生したアイテムの総和を表示
        $totalGachaResults = [];
        foreach ($itemCounts as $itemId => $count) {
            $totalGachaResults[] = [
                'itemId' => $itemId,
                'count' => $count,
            ];
        }

        // プレイヤーのアイテムデータを取得
        //$playerItems = $this->getPlayerItemsData($player);

        // レスポンスを返す
        return response()->json([
            'results' => $totalGachaResults,
            'player' => [
                'money' => $player->money,
                'items' => $id = PlayerItems::select('item_id as itemId', 'count')->get(),
            ],
        ]);
    }
}