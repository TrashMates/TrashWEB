<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class ToolController extends Controller
{

	/**
	 * Show the 'Currently Streamed Games Stats' Tool
	 *
	 * @return View
	 */
	public function game(): View
	{
		return view("admin.tool.game");
	}


	/**
	 * Show the 'Followers Stalker' Tool
	 * 
	 * @return View
	 */
	public function stalker(Request $request): View
	{
		return view("admin.tool.stalker");
	}
}
