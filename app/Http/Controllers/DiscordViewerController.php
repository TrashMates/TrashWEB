<?php

namespace App\Http\Controllers;

use App\Models\DiscordViewer;
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
		$title = "Discord Viewers List";

		$count = DiscordViewer::count();
		$page = $request->get("page") ?? 1;

		$Viewers = DiscordViewer::skip(($page - 1) * 50)
			->take(50)
			->orderBy("created_at", "DESC")
			->get();

		return view("admin.viewers.index", compact("Viewers", "title", "count", "page"));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $viewerID
	 * @return DiscordViewer|JsonResponse
	 */
	public function show($viewerID)
	{
		$Viewer = DiscordViewer::with(['events', 'messages'])->findOrFail($viewerID);
		$title = "Discord Viewer - " . $Viewer->username . "#" . $Viewer->discriminator;

		return view("admin.viewers.show", compact('Viewer', 'title'));
	}

}
