<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use TiCubius\TwitchAPI\Facades\Users;

class WebController extends Controller
{

    /**
     * GET - Shows the welcome page
     *
     * @return View
     */
    public function index(): View
    {
        return view("web.index");
    }

}
