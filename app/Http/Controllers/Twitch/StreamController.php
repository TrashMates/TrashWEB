<?php

namespace App\Http\Controllers\Twitch;

use App\Http\Controllers\Controller;
use App\Models\Twitch\Stream;
use Illuminate\View\View;

class StreamController extends Controller
{


    /**
     * GET - Shows a specific Twitch stream
     *
     * @param Stream $stream
     * @return View
     */
    public function show(Stream $stream): View
    {
        $stream->load("user", "metadata");

        return view("web.twitch.streams.show", compact("stream"));
    }

}
