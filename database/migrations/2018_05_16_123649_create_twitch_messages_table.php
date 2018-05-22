<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitchMessagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('twitch_messages', function(Blueprint $table) {
			$table->increments("id");
			$table->bigInteger("userid");
			$table->string("channel");
			$table->longText("content");
			$table->timestamps();

			$table->foreign("userid")->references("id")->on("twitch_viewers");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("twitch_messages");
	}
}
