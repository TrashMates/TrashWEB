<?php

namespace App\Http\Controllers;

use App\Models\TwitchMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TwitchMessageController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @param Request $request
	 * @return Collection
	 */
	public function index(Request $request)
	{
		$title = "Latest Twitch Messages";

		$count = TwitchMessage::count();
		$page = $request->get("page") ?? 1;

		$Messages = TwitchMessage::with("viewer")->skip(($page - 1) * 50)->take(50)->orderBy("created_at", "DESC")->get();

		return view("admin.messages.index", compact("Messages", "title", "count", "page"));
	}

}
