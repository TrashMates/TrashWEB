<?php

namespace App\Http\Controllers\Api;

use App\TwitchEvent;
use App\TwitchMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TwitchMessageController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 *
	 * @return Collection
	 */
	public function index(Request $request)
	{
		$skip = $request->header("skip") ?? $request->get("skip") ?? 0;
		$join = $request->header("join") ? true : $request->has("join") ?? false;
		$limit = $request->header("limit") ?? $request->get("limit") ?? env("API_DEFAULT_ENTITIES");

		// QUERYING MESSAGE - Should we get the Viewer as well ?
		if ($join) {
			$messages = TwitchMessage::take($limit)->skip($skip)->with("viewer", "events")->get();
		} else {
			$messages = TwitchMessage::take($limit)->skip($skip)->get();
		}

		return $messages;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 *
	 * @return TwitchMessage|JsonResponse
	 */
	public function store(Request $request)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("userid")) || empty($request->input("channel")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// INVALID REQUEST - MESSAGE ALREADY EXISTS
		if (TwitchMessage::find($request->input("userid"))) {
			return response(["error" => "403 - A Twitch Message is already registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE THE MESSAGE
		$message = new TwitchMessage([
			"userid" => $request->input("userid"),
			"channel" => $request->input("channel"),
			"content" => $request->input("content"),
		]);

		// SAVE THE CREATED MESSAGE
		$message->save();

		return $message;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Request $request
	 * @param int     $messageID
	 *
	 * @return TwitchMessage|JsonResponse
	 */
	public function show(Request $request, int $messageID)
	{
		$join = $request->header("join") ? true : $request->has("join") ?? false;

		// QUERYING MESSAGE - Should we get the Viewer as well ?
		if ($join) {
			$message = TwitchMessage::with("viewer", "events")->find($messageID);
		} else {
			$message = TwitchMessage::find($messageID);
		}

		// VALID REQUEST - MESSAGE EXISTS
		if ($message) {
			return $message;
		}

		// INVALID REQUEST - MESSAGE DOESN'T EXIST
		return response(["error" => "404 - The Twitch Message you requested does not exist."], 404);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 * @param int      $messageID
	 *
	 * @return TwitchMessage|JsonResponse
	 */
	public function update(Request $request, int $messageID)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("userid")) || empty($request->input("channel")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// SEARCHING FOR THE MESSAGE
		$message = TwitchMessage::find($messageID);

		// INVALID REQUEST - MESSAGE DOESN'T EXIST
		if (!$message) {
			return response(["error" => "403 - No Twitch Message is registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE AN EVENT
		if ($message->content !== $request->input("content")) {
			$event = new TwitchEvent([
				"userid" => $message->userid,
				"messageid" => $message->id,
				"type" => "MESSAGE_MODIFIED",
				"content" => $message->id . " was modified (before: " . $message->content . ")"
			]);

			$event->save();
		}

		// CHANGE THE MESSAGE
		$message->id = $messageID;
		$message->userid = $request->input("userid");
		$message->channel = $request->input("channel");
		$message->content = $request->input("content");
		$message->updated_at = Carbon::now();
		$message->save();

		return $message;
	}
}
