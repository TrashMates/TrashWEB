<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\TwitchGame;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;

class TwitchController extends Controller
{
	/**
	 * Fetches a Game thanks to its name, from Twitch
	 *
	 * @param $name
	 * @return object
	 */
	public static function fetchGameFromName(string $name): object
	{
		$oauth = Session::get("twitch_code");

		return Curl::to("https://api.twitch.tv/helix/games")
			       ->withHeader("Authorization: Bearer ${oauth}")
			       ->withData(["name" => $name])
			       ->asJSON()
			       ->get()->data[0];
	}

	/**
	 * [RECURSIVE] - Fetches channels from their IDs
	 *
	 * @param Collection      $channelIDs
	 * @param Collection|null $channels
	 * @return object
	 */
	public static function fetchChannelsFromID(Collection $channelIDs, Collection $channels = null): object
	{
		$oauth = Session::get("twitch_code");

		if ($channelIDs->isEmpty()) {
			return new Collection();
		}

		if ($channels === null) {
			$channels = collect();
		}

		// Generate URL parameter
		$channel_string = $channelIDs->slice(0, 100)->implode("&id=");

		// Fetch from Twitch
		$data = Curl::to("https://api.twitch.tv/helix/users?id=${channel_string}")
			->withHeader("Authorization: Bearer ${oauth}")
			->asJSON()// ->enableDebug(public_path("debug.txt"))
			->get();

		// Add each channel to the channels collection
		foreach ($data->data as $channel) {
			$channels->push($channel);
		}

		// Recursive
		$channelIDs = $channelIDs->slice(99);
		if ($channelIDs->isNotEmpty()) {
			return self::fetchChannelsFromID($channelIDs, $channels);
		}

		return $channels;
	}

	/**
	 * [RECURSIVE] - Fetches up to 15000 streams of Game
	 *
	 * @param TwitchGame $game
	 * @param Collection $streams
	 * @param string     $pagination
	 * @return Collection
	 */
	public static function fetchGameStreams(TwitchGame $game, Collection $streams = null, string $pagination = ""): Collection
	{
		$oauth = Session::get("twitch_code");

		if ($streams === null) {
			$streams = collect();
		}

		// Fetch streams from Twitch
		$data = Curl::to("https://api.twitch.tv/helix/streams")
			->withHeader("Authorization: Bearer ${oauth}")
			->withData(["game_id" => $game->id, "first" => "100", "after" => $pagination])
			->asJSON()
			->get();

		// Add each stream to streams collection
		foreach ($data->data as $stream) {
			$streams->push($stream);
		}

		// Recursive
		if (count($data->data) >= 100) {
			$pagination = $data->pagination->cursor;

			return self::fetchGameStreams($game, $streams, $pagination);
		}

		return ($streams);
	}
}
