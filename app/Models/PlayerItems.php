<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerItems extends Model
{   
    // PlayerItemsモデル
    protected $fillable = ['player_id', 'item_id', 'count'];
    protected $table = 'player_items';
    public $timestamps = false;
    use HasFactory;
}