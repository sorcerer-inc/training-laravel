<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Http\Resources\ItemResource;
use App\Models\Player;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class PlayerItemsController extends Controller
{
    public function index()
    {
        // players テーブルと items テーブルを結合してデータを取得
        $playersWithItems = Player::join('items', 'players.id', '=', 'items.player_id')
            ->select('players.*', 'items.name as item_name')
            ->get();

        // 結果をビューに渡す
        return view('players.index', ['playersWithItems' => $playersWithItems]);
    }
}
