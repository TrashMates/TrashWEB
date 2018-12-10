<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\TwitchChannel;
use App\Models\TwitchGame;
use App\Models\TwitchGameStat;
use App\Models\TwitchGameStream;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
	/**
	 * GET - Fetches all games
	 *
	 * @return View
	 */
	public function index(): View
	{
		$games = TwitchGame::orderBy("name")->get();

		return view("admin.tools.games.index", compact("games"));
	}

	/**
	 * POST - Registers a new Game
	 *
	 * @param Request $request
	 * @return TwitchGame
	 */
	public function store(Request $request)
	{
		$request->validate([
			"name" => "required|max:255|unique:twitch_games",
		]);

		$twitch_game = TwitchController::fetchGameFromName($request->name);
		$game = TwitchGame::create([
			"id"      => $twitch_game->id,
			"name"    => $twitch_game->name,
			"picture" => $twitch_game->box_art_url,
		]);

		// ID is 0 unless
		$game->id = $twitch_game->id;

		return $game;
	}

	/**
	 * GET - Fetches a specific game
	 *
	 * @param int $id
	 * @return View
	 */
	public function show(int $id): View
	{
		$game = TwitchGame::with("stats")->find($id);

		return view("admin.tools.games.show", compact('game'));
	}

	/**
	 * PUT - Scans the game
	 *
	 * @param TwitchGame $game
	 * @return TwitchGame
	 */
	public function update(TwitchGame $game)
	{
		$streams = TwitchController::fetchGameStreams($game);
		$stat = TwitchGameStat::create(["game_id" => $game->id]);

		// Create all channels
		$channelIDs = $streams->pluck("user_id");
		$channels = TwitchController::fetchChannelsFromID($channelIDs);

		foreach ($channels as $channel) {
			if (!TwitchChannel::find($channel->id)) {
				TwitchChannel::create([
					"id"          => $channel->id,
					"username"    => $channel->display_name,
					"description" => $channel->description,
					"type"        => $channel->broadcaster_type,
					"picture"     => $channel->profile_image_url,
				]);
			}
		}

		// Create all streams
		foreach ($streams as $twitchStream) {
			$stream = TwitchGameStream::find($twitchStream->id);
			if (!$stream) {
				$stream = new TwitchGameStream([
					"id"         => $twitchStream->id,
					"channel_id" => $twitchStream->user_id,
					"title"      => $twitchStream->title,
					"language"   => $twitchStream->language,
					"viewers"    => $twitchStream->viewer_count,
					"created_at" => Carbon::parse($twitchStream->started_at),
				]);
			}

			$stream->game()->associate($game);
			$stream->stat()->associate($stat);
			$stream->save();
		}

		return $stat->with('streams')->find($stat->id);
	}
}
