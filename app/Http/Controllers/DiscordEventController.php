<?php

namespace App\Http\Controllers;

use App\DiscordEvent;
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
	 *
	 * @return Collection
	 */
	public function index(Request $request)
	{
		$title = "Latest Discord Events";

		$count = DiscordEvent::count();
		$page = $request->get("page") ?? 1;

		$Events = DiscordEvent::with("viewer")->skip(($page -1) * 50)->take(50)->orderBy("created_at", "DESC")->get();

		return view("admin.events.index", compact("Events", "title", "count", "page"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 *
	 * @return DiscordEvent|JsonResponse
	 */
	public function store(Request $request)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("userid")) || empty($request->input("type")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// INVALID REQUEST - EVENT ALREADY EXISTS
		if (DiscordEvent::find($request->input("userid"))) {
			return response(["error" => "403 - A Discord Event is already registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE THE EVENT
		$event = new DiscordEvent([
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
	 *
	 * @return DiscordEvent|JsonResponse
	 */
	public function show(Request $request, int $eventID)
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
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 * @param int      $eventID
	 *
	 * @return DiscordEvent|JsonResponse
	 */
	public function update(Request $request, int $eventID)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("userid")) || empty($request->input("type")) || empty($request->input("content"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// SEARCHING FOR THE EVENT
		$event = DiscordEvent::find($eventID);

		// INVALID REQUEST - EVENT DOESN'T EXIST
		if (!$event) {
			return response(["error" => "403 - No Discord Event is registered with this ID."], 403);
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
}