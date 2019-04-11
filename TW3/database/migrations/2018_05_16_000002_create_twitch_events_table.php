<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitchEventsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('twitch_events', function(Blueprint $table) {
			$table->increments("id");
			$table->bigInteger("viewer_id");
			$table->integer("message_id")->unsigned()->nullable();
			$table->string("type");
			$table->string("content");
			$table->timestamps();

			$table->foreign("viewer_id")->references("id")->on("twitch_viewers");
			$table->foreign("message_id")->references("id")->on("twitch_messages");
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
