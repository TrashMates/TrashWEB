<?php

namespace App\Http\Controllers;

use App\TwitchViewer;
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
		$title = "Twitch Viewers List";

		$count = TwitchViewer::count();
		$page = $request->get("page") ?? 1;

		$Viewers = TwitchViewer::skip(($page - 1) * 50)->take(50)->orderBy("created_at", "DESC")->get();

		return view("admin.viewers.index", compact("Viewers", "title", "count", "page"));
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
		$Viewer = TwitchViewer::with(['events', 'messages'])->findOrFail($viewerID);
		$title = "Twitch Viewer - " . $Viewer->username;

		return view("admin.viewers.show", compact('Viewer', 'title'));
	}

}
