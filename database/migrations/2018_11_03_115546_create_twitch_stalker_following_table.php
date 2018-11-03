<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitchStalkerFollowingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitch_stalker_following', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('follow_from_id');
            $table->foreign('follow_from_id')->references('id')->on('twitch_stalker_users');

            
            $table->bigInteger('follow_to_id');
            $table->foreign('follow_to_id')->references('id')->on('twitch_stalker_users');

            $table->timestamp('followed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('twitch_stalker_following');
    }
}
