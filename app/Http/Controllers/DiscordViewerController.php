<?php

namespace App\Http\Controllers;

use App\DiscordEvent;
use App\DiscordViewer;
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
	 *
	 * @return Collection
	 */
	public function index(Request $request)
	{
		$title = "Discord Viewers List";

		$count = DiscordViewer::count();
		$page = $request->get("page") ?? 1;

		$Viewers = DiscordViewer::skip(($page -1) * 50)->take(50)->orderBy("created_at", "DESC")->get();

		return view("admin.viewers.index", compact("Viewers", "title", "count", "page"));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 *
	 * @return DiscordViewer|JsonResponse
	 */
	public function store(Request $request)
	{
		// INVALID REQUEST - MISSING INFORMATIONS
		if (empty($request->input("userid")) || empty($request->input("username")) || empty($request->input("discriminator")) || empty($request->input("role"))) {
			return response(["error" => "400 - Your request is incomplete."], 400);
		}

		// INVALID REQUEST - VIEWER ALREADY EXISTS
		if (DiscordViewer::find($request->input("userid"))) {
			return response(["error" => "403 - A Discord Viewer is already registered with this ID."], 403);
		}

		// VALID REQUEST - CREATE THE VIEWER
		$viewer = new DiscordViewer([
			"id" => $request->input("userid"),
			"username" => $request->input("username"),
			"discriminator" => $request->input("discriminator"),
			"role" => $request->input("role"),
			"created_at" => Carbon::parse($request->input("created_at")) ?? Carbon::now(),
		]);
		$viewer->save();

		// CREATE THE EVENT
		$event = new DiscordEvent([
			"userid" => $request->input("userid"),
			"type" => "VIEWER_CREATED",
			"content" => $request->input("username") . "#" . $request->input("discriminator") . " has joined the server"
		]);
		$event->save();

		return $viewer;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Request $request
	 * @param int     $viewerID
	 *
	 * @return DiscordViewer|JsonResponse
	 */
	public function show(Request $request, int $viewerID)
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
	 *
	 * @param int      $viewerID
	 *
	 * @return DiscordViewer|JsonResponse
	 */
	public function update(Request $request, int $viewerID)
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
				"userid" => $viewerID,
				"type" => "VIEWER_UPDATED",
				"content" => $viewer->username . "#" . $viewer->discriminator . " changed his username (became " . $request->input("username") . "#" . $request->input("discriminator") . ")"
			]);
			$event->save();
		}

		if ($viewer->role != $request->input("role")) {
			$event = new DiscordEvent([
				"userid" => $viewerID,
				"type" => "VIEWER_UPDATED",
				"content" => $viewer->username . " changed role (from " . $viewer->role . " to " . $request->input("role") . ")"
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
}
