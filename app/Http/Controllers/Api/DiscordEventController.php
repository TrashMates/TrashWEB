<?php

namespace App\Http\Controllers\Api;

use App\DiscordEvent;
use App\DiscordViewer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DiscordEventController extends Controller
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
			$events = DiscordEvent::take($limit)->skip($skip)->with("viewer")->get();
		} else {
			$events = DiscordEvent::take($limit)->skip($skip)->get();
		}

		return $events;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 * @return DiscordEvent|JsonResponse
	 */
	public function store(Request $request)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("viewer_id")) || empty($request->input("type")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// INVALID REQUEST - VIEWER DOESN'T EXIST
		if (!DiscordViewer::find($request->input('viewer_id'))) {
			return response(["error" => "403 - No Discord Viewer is registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE THE EVENT
		$event = new DiscordEvent([
			"viewer_id"  => $request->input("viewer_id"),
			"message_id" => $request->input("message_id") ?? null,
			"type"       => $request->input("type"),
			"content"    => $request->input("content"),
			"created_at" => Carbon::parse($request->input("created_at")) ?? Carbon::now(),
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
	 * @return DiscordEvent|JsonResponse
	 */
	public function show(Request $request, $eventID)
	{
		$join = $request->header("join") ? true : $request->has("join") ?? false;

		// QUERYING EVENT - Should we get the Viewer as well ?
		if ($join) {
			$event = DiscordEvent::with("viewer")->find($eventID);
		} else {
			$event = DiscordEvent::find($eventID);
		}

		// VALID REQUEST - EVENT EXISTS
		if ($event) {
			return $event;
		}

		// INVALID REQUEST - EVENT DOESN'T EXIST
		return response(["error" => "404 - The Discord Event you requested does not exist."], 404);
	}

	/**
	 * GET - Request the statistics
	 *
	 * @return Collection
	 */
	public function stats()
	{
		return DiscordEvent::getStats()->where("created_at", ">", Carbon::now()->subDay(31))->get();
	}
}
