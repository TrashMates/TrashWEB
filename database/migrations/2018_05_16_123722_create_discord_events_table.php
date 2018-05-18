<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscordEventsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discord_events', function (Blueprint $table) {
			$table->increments("id");
			$table->bigInteger("userid");
			$table->bigInteger("messageid")->nullable();
			$table->string("type");
			$table->string("content");
			$table->timestamps();

			$table->foreign("userid")->references("id")->on("discord_viewers");
			$table->foreign("messageid")->references("id")->on("discord_messages");
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
