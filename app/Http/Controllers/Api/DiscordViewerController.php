<?php

namespace App\Http\Controllers\Api;

use App\Models\DiscordEvent;
use App\Models\DiscordViewer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DiscordViewerController extends Controller
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
			$viewers = DiscordViewer::take($limit)->skip($skip)->with("messages", "events")->get();
		} else {
			$viewers = DiscordViewer::take($limit)->skip($skip)->get();
		}

		return $viewers;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 * @return DiscordViewer|JsonResponse
	 */
	public function store(Request $request)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("id")) || empty($request->input("username")) || empty($request->input("discriminator")) || empty($request->input("role"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// INVALID REQUEST - VIEWER ALREADY EXISTS
		if (DiscordViewer::find($request->input("id"))) {
			return response(["error" => "403 - A Discord Viewer is already registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE THE VIEWER
		$viewer = new DiscordViewer([
			"id"            => $request->input("id"),
			"username"      => $request->input("username"),
			"discriminator" => $request->input("discriminator"),
			"role"          => $request->input("role"),
			"created_at"    => Carbon::parse($request->input("created_at")) ?? Carbon::now(),
		]);
		$viewer->save();

		// CREATE THE EVENT
		$event = new DiscordEvent([
			"viewer_id"  => $request->input("id"),
			"type"       => "VIEWER_CREATED",
			"content"    => $request->input("username") . "#" . $request->input("discriminator") . " has joined the server",
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
	 * @return DiscordViewer|JsonResponse
	 */
	public function show(Request $request, $viewerID)
	{
		$join = $request->header("join") ? true : $request->has("join") ?? false;

		// QUERYING VIEWER - Should we get the Messages as well ?
		if ($join) {
			$viewer = DiscordViewer::with("messages", "events")->find($viewerID);
		} else {
			$viewer = DiscordViewer::find($viewerID);
		}

		// VALID REQUEST - VIEWER EXISTS
		if ($viewer) {
			return $viewer;
		}

		// INVALID REQUEST - VIEWER DOESN'T EXIST
		return response(["error" => "404 - The Discord Viewer you requested does not exist."], 404);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request $request
	 * @param int      $viewerID
	 * @return DiscordViewer|JsonResponse
	 */
	public function update(Request $request, $viewerID)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("username")) || empty($request->input("discriminator")) || empty($request->input("role"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// SEARCHING FOR THE VIEWER
		$viewer = DiscordViewer::find($viewerID);

		// INVALID REQUEST - VIEWER DOESN'T EXIST
		if (!$viewer) {
			return response(["error" => "403 - No Discord Viewer is registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE EVENTS
		if ($viewer->username != $request->input("username") || $viewer->discriminator != $request->input("discriminator")) {
			$event = new DiscordEvent([
				"viewer_id" => $viewerID,
				"type"      => "VIEWER_UPDATED",
				"content"   => $viewer->username . "#" . $viewer->discriminator . " changed his username (became " . $request->input("username") . "#" . $request->input("discriminator") . ")",
			]);
			$event->save();
		}

		if ($viewer->role != $request->input("role")) {
			$event = new DiscordEvent([
				"viewer_id" => $viewerID,
				"type"      => "VIEWER_UPDATED",
				"content"   => $viewer->username . " changed role (from " . $viewer->role . " to " . $request->input("role") . ")",
			]);
			$event->save();
		}

		// UPDATE THE VIEWER
		$viewer->id = $viewerID;
		$viewer->username = $request->input("username");
		$viewer->discriminator = $request->input("discriminator");
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
		return DiscordViewer::getStats()->where("created_at", ">", Carbon::now()->subDay(31))->get();
	}
}
