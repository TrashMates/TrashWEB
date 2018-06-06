<?php

namespace App\Http\Controllers\Api;

use App\TwitchEvent;
use App\TwitchViewer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TwitchViewerController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Collection
	 */
	public function index(Request $request)
	{
		$limit = $request->header("limit") ?? $request->get("limit") ?? env("API_DEFAULT_ENTITIES");
		$skip = $request->header("skip") ?? $request->get("skip") ?? 0;
		$join = $request->header("join") ? true : $request->has("join") ?? false;

		// QUERYING VIEWER - Should we get the Messages as well ?
		if ($join) {
			$viewers = TwitchViewer::take($limit)->skip($skip)->with("messages", "events")->get();
		} else {
			$viewers = TwitchViewer::take($limit)->skip($skip)->get();
		}

		return $viewers;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 * @return TwitchViewer|JsonResponse
	 */
	public function store(Request $request)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("id")) || empty($request->input("username")) || empty($request->input("role"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// INVALID REQUEST - VIEWER ALREADY EXISTS
		if (TwitchViewer::find($request->input("id"))) {
			return response(["error" => "403 - A Twitch Viewer is already registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE THE VIEWER
		$viewer = new TwitchViewer([
			"id"         => $request->input("id"),
			"username"   => $request->input("username"),
			"role"       => $request->input("role"),
			"created_at" => Carbon::parse($request->input("created_at")) ?? Carbon::now(),
		]);
		$viewer->save();

		// CREATE THE EVENT
		$event = new TwitchEvent([
			"viewer_id"  => $request->input("id"),
			"type"       => "VIEWER_CREATED",
			"content"    => $request->input("username") . " has been created",
			"created_at" => Carbon::parse($request->input("created_at")) ?? Carbon::now(),
		]);
		$event->save();

		return $viewer;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Request $request
	 * @param int     $viewerID
	 * @return TwitchViewer|JsonResponse
	 */
	public function show(Request $request, int $viewerID)
	{
		$join = $request->header("join") ? true : $request->has("join") ?? false;

		// QUERYING VIEWER - Should we get the Messages as well ?
		if ($join) {
			$viewer = TwitchViewer::with("messages", "events")->find($viewerID);
		} else {
			$viewer = TwitchViewer::find($viewerID);
		}

		// VALID REQUEST - VIEWER EXISTS
		if ($viewer) {
			return $viewer;
		}

		// INVALID REQUEST - VIEWER DOESN'T EXIST
		return response(["error" => "404 - The Twitch Viewer you requested does not exist."], 404);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 * @param int      $viewerID
	 * @return TwitchViewer|JsonResponse
	 */
	public function update(Request $request, int $viewerID)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("username")) || empty($request->input("role"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// SEARCHING FOR THE VIEWER
		$viewer = TwitchViewer::find($viewerID);

		// INVALID REQUEST - VIEWER DOESN'T EXIST
		if (!$viewer) {
			return response(["error" => "403 - No Twitch Viewer is registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE EVENTS
		if ($viewer->username != $request->input("username")) {
			$event = new TwitchEvent([
				"viewer_id" => $viewerID,
				"type"      => "VIEWER_UPDATED",
				"content"   => $viewer->username . " changed his username (became " . $request->input("username") . ")",
			]);
			$event->save();
		}

		if ($viewer->role != $request->input("role")) {
			$event = new TwitchEvent([
				"viewer_id" => $viewerID,
				"type"      => "VIEWER_UPDATED",
				"content"   => $viewer->username . " changed role (from " . $viewer->role . " to " . $request->input("role") . ")",
			]);
			$event->save();
		}

		// UPDATE THE VIEWER
		$viewer->id = $viewerID;
		$viewer->username = $request->input("username");
		$viewer->role = $request->input("role");
		$viewer->updated_at = Carbon::now();
		$viewer->save();

		return $viewer;
	}


	/**
	 * GET - Request the statistics
	 *
	 * @return Collection
	 */
	public function stats()
	{
		return TwitchViewer::getStats()->where("created_at", ">", Carbon::now()->subDay(31))->get();
	}
}
