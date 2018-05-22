<?php

namespace App\Http\Controllers;

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
	 *
	 * @return Collection
	 */
	public function index(Request $request)
	{
		$title = "Latest Twitch Events";

		$count = TwitchEvent::count();
		$page = $request->get("page") ?? 1;

		$Events = TwitchEvent::with("viewer")->skip(($page -1) * 50)->take(50)->orderBy("created_at", "DESC")->get();

		return view("admin.events.index", compact("Events", "title", "count", "page"));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Request $request
	 * @param int     $eventID
	 *
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

}
