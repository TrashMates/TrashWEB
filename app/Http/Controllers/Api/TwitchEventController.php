<?php

namespace App\Http\Controllers\Api;

use App\TwitchEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TwitchEventController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Collection
	 */
	public function index(Request $request)
	{
		$skip = $request->header("skip") ?? $request->get("skip") ?? 0;
		$join = $request->header("join") ? true : $request->has("join") ?? false;
		$limit = $request->header("limit") ?? $request->get("limit") ?? env("API_DEFAULT_ENTITIES");

		// QUERYING EVENT - Should we get the Viewer as well ?
		if ($join) {
			$events = TwitchEvent::take($limit)->skip($skip)->with("viewer")->get();
		} else {
			$events = TwitchEvent::take($limit)->skip($skip)->get();
		}

		return $events;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 * @return TwitchEvent|JsonResponse
	 */
	public function store(Request $request)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("userid")) || empty($request->input("type")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// INVALID REQUEST - EVENT ALREADY EXISTS
		if (TwitchEvent::find($request->input("userid"))) {
			return response(["error" => "403 - A Twitch Event is already registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE THE EVENT
		$event = new TwitchEvent([
			"userid" => $request->input("userid"),
			"type" => $request->input("type"),
			"content" => $request->input("content"),
		]);

		// SAVE THE CREATED EVENT
		$event->save();

		return $event;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Request $request
	 * @param int     $eventID
	 * @return TwitchEvent|JsonResponse
	 */
	public function show(Request $request, int $eventID)
	{
		$join = $request->header("join") ? true : $request->has("join") ?? false;

		// QUERYING EVENT - Should we get the Viewer as well ?
		if ($join) {
			$event = TwitchEvent::with("viewer")->find($eventID);
		} else {
			$event = TwitchEvent::find($eventID);
		}

		// VALID REQUEST - EVENT EXISTS
		if ($event) {
			return $event;
		}

		// INVALID REQUEST - EVENT DOESN'T EXIST
		return response(["error" => "404 - The Twitch Event you requested does not exist."], 404);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 * @param int      $eventID
	 * @return TwitchEvent|JsonResponse
	 */
	public function update(Request $request, int $eventID)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("userid")) || empty($request->input("type")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// SEARCHING FOR THE EVENT
		$event = TwitchEvent::find($eventID);

		// INVALID REQUEST - EVENT DOESN'T EXIST
		if (!$event) {
			return response(["error" => "403 - No Twitch Event is registered with this ID."], 403);
		}

		// VALID REQUEST - CHANGE THE EVENT
		$event->id = $eventID;
		$event->userid = $request->input("userid");
		$event->type = $request->input("type");
		$event->content = $request->input("content");
		$event->updated_at = Carbon::now();

		// SAVE THE CHANGED EVENT
		$event->save();

		return $event;
	}


	/**
	 * GET - Request the statistics
	 *
	 * @return Collection
	 */
	public function stats()
	{
		return TwitchEvent::getStats()->where("created_at", ">", Carbon::now()->subDay(31))->get();
	}
}
