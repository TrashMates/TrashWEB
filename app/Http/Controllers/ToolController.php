<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class ToolController extends Controller
{
    public function game()
    {
    	return view("admin.tool.game");
    }

    public function gameTest()
    {

    }
}
