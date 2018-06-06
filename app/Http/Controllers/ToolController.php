<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

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

}
