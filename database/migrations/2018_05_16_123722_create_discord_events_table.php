<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscordEventsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discord_events', function(Blueprint $table) {
			$table->increments("id");
			$table->bigInteger("viewer_id");
			$table->bigInteger("message_id")->nullable();
			$table->string("type");
			$table->string("content");
			$table->timestamps();

			$table->foreign("viewer_id")->references("id")->on("discord_viewers");
			$table->foreign("message_id")->references("id")->on("discord_messages");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("discord_events");
	}
}
