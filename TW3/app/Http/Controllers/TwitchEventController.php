<?php

namespace App\Http\Controllers;

use App\Models\TwitchEvent;
use Illuminate\Database\Eloquent\Collection;
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
		$title = "Latest Twitch Events";

		$count = TwitchEvent::count();
		$page = $request->get("page") ?? 1;

		$Events = TwitchEvent::with("viewer")
			->skip(($page - 1) * 50)
			->take(50)
			->orderBy("created_at", "DESC")
			->get();

		return view("admin.events.index", compact("Events", "title", "count", "page"));
	}

}
