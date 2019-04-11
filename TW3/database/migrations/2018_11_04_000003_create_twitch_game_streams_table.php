<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTwitchGameStreamsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('twitch_game_streams', function(Blueprint $table) {
			$table->bigInteger('id');

			$table->unsignedInteger('stat_id');
			$table->foreign('stat_id')->references('id')->on('twitch_game_stats');

			$table->bigInteger('game_id');
			$table->foreign('game_id')->references('id')->on('twitch_games');

			$table->bigInteger('channel_id');
			$table->foreign('channel_id')->references('id')->on('twitch_channels');

			$table->string('title');
			$table->string('language');
			$table->integer('viewers');

			$table->timestamp('created_at');
			$table->primary('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('twitch_game_streams');
	}
}
