<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscordMessagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discord_messages', function(Blueprint $table) {
			$table->bigInteger("id");
			$table->bigInteger("userid");
			$table->string("channel");
			$table->longText("content");
			$table->timestamps();

			$table->primary("id");
			$table->foreign("userid")->references("id")->on("discord_viewers");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("discord_messages");
	}
}
