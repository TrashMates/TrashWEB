<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitchEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitch_events', function (Blueprint $table) {
	        $table->increments("id");
	        $table->bigInteger("userid");
	        $table->integer("messageid")->unsigned()->nullable();
	        $table->string("type");
	        $table->string("content");
	        $table->timestamps();

	        $table->foreign("userid")->references("id")->on("twitch_viewers");
	        $table->foreign("messageid")->references("id")->on("twitch_messages");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists("twitch_events");
    }
}
