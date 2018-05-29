<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ixudra\Curl\Facades\Curl;

class ToolController extends Controller
{
	/**
	 * GET - Request all the current Twitch Streams for a Game
	 *
	 * @param Request $request
	 * @param null    $gameid
	 * @param null    $cursor
	 * @param array   $streams
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function game(Request $request, $gameid = null, $cursor = null, $streams = [])
	{
		if ($request->has("game") || $gameid) {
			// QUERY GAME
			if (!$gameid) {
				$gameAPI = Curl::to("https://api.twitch.tv/helix/games")
					->withHeader("Client-ID: " . env("TWITCH_CLIENTID"))
					->withData(["name" => $request->input("game")])
					->asJSON()
					->get();

				$gameid = $gameAPI->data[0]->id;
			}

			// QUERY STREAMS
			if (!$cursor) {
				$streamsAPI = Curl::to("https://api.twitch.tv/helix/streams")
					->withHeader("Client-ID: " . env("TWITCH_CLIENTID"))
					->withData(["game_id" => $gameid, "first" => "100"])
					->asJSON()
					->get();
			} else {
				$streamsAPI = Curl::to("https://api.twitch.tv/helix/streams")
					->withHeader("Client-ID: " . env("TWITCH_CLIENTID"))
					->withData(["game_id" => $gameid, "first" => "100", "after" => $cursor])
					->asJSON()
					->get();
			}

			if (empty($streamsAPI->data)) {
				return response()->json($streams);
			} else {
				foreach ($streamsAPI->data as $stream) {
					$streams[] = $stream;
				}

				$cursor = $streamsAPI->pagination->cursor;
				return $this->game($request, $gameid, $cursor, $streams);
			}
		}

	}

}
