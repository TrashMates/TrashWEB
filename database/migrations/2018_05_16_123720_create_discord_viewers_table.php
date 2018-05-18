<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscordViewersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('discord_viewers', function (Blueprint $table) {
			$table->bigInteger("id");
			$table->string("username");
			$table->string("discriminator", 4);
			$table->string("role");
			$table->timestamps();

			$table->primary("id");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists("discord_viewers");
	}
}
