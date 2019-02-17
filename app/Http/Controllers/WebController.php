<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

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
