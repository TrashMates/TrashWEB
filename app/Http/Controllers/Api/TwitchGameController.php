<?php

namespace App\Http\Controllers\Api;

use App\Models\TwitchGame;
use App\Models\TwitchGameStat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TwitchGameController extends Controller
{

	/**
	 * @param TwitchGame $game
	 * @return TwitchGame
	 */
	public function game(TwitchGame $game): TwitchGame
	{
		return $game;
	}

	/**
	 * @param int $game
	 * @return TwitchGame|TwitchGame[]
	 */
	public function stats(int $game)
	{
		return TwitchGame::with("stats")->findOrFail($game);
	}

	/**
	 * GET - Fetches a specific stat and streams for
	 *
	 * @param int $game
	 * @param int $stat
	 * @return TwitchGameStat
	 */
	public function stat(int $game, int $stat): TwitchGameStat
	{
		return TwitchGameStat::with("streams", "streams.channel")->findOrFail($stat);
	}

}
