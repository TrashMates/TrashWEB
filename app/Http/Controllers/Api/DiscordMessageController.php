<?php

namespace App\Http\Controllers\Api;

use App\DiscordEvent;
use App\DiscordMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DiscordMessageController extends Controller
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
			$messages = DiscordMessage::take($limit)->skip($skip)->with("viewer", "events")->get();
		} else {
			$messages = DiscordMessage::take($limit)->skip($skip)->get();
		}

		return $messages;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 *
	 * @return DiscordMessage|JsonResponse
	 */
	public function store(Request $request)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("id")) || empty($request->input("userid")) || empty($request->input("channel")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// INVALID REQUEST - MESSAGE ALREADY EXISTS
		if (DiscordMessage::find($request->input("userid"))) {
			return response(["error" => "403 - A Discord Message is already registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE THE MESSAGE
		$message = new DiscordMessage([
			"id" => $request->input("id"),
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
	 * @return DiscordMessage|JsonResponse
	 */
	public function show(Request $request, int $messageID)
	{
		$join = $request->header("join") ? true : $request->has("join") ?? false;

		// QUERYING MESSAGE - Should we get the Viewer as well ?
		if ($join) {
			$message = DiscordMessage::with("viewer", "events")->find($messageID);
		} else {
			$message = DiscordMessage::find($messageID);
		}

		// VALID REQUEST - MESSAGE EXISTS
		if ($message) {
			return $message;
		}

		// INVALID REQUEST - MESSAGE DOESN'T EXIST
		return response(["error" => "404 - The Discord Message you requested does not exist."], 404);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 * @param int      $messageID
	 *
	 * @return DiscordMessage|JsonResponse
	 */
	public function update(Request $request, int $messageID)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("userid")) || empty($request->input("channel")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// SEARCHING FOR THE MESSAGE
		$message = DiscordMessage::find($messageID);

		// INVALID REQUEST - MESSAGE DOESN'T EXIST
		if (!$message) {
			return response(["error" => "403 - No Discord Message is registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE AN EVENT
		if ($message->content !== $request->input("content")) {
			$event = new DiscordEvent([
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
