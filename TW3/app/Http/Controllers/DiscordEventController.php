<?php

namespace App\Http\Controllers;

use App\Models\DiscordEvent;
use Illuminate\Database\Eloquent\Collection;
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
		$title = "Latest Discord Events";

		$count = DiscordEvent::count();
		$page = $request->get("page") ?? 1;

		$Events = DiscordEvent::with("viewer")
			->skip(($page - 1) * 50)
			->take(50)
			->orderBy("created_at", "DESC")
			->get();

		return view("admin.events.index", compact("Events", "title", "count", "page"));
	}

}
