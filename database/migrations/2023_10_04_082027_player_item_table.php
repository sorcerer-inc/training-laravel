<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlayerItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            
            $table->mediumIncrements('player_id')->comment("プレイヤーID");
            $table->mediumIncrements('item_id')->comment("アイテムID");
            $table->string('player_name')->comment("プレイヤー名");
            $table->string('item_name')->comment("アイテム名");
            $table->$table->integer('count')->comment("所持個数");

            $table->primary(['player_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
