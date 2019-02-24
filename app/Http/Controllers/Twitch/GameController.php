<?php

namespace App\Http\Controllers\Twitch;

use App\Http\Controllers\Controller;
use App\Models\Twitch\Game;
use App\Models\Twitch\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;

class GameController extends Controller
{

    /**
     * GET - Shows all Twitch users
     * GET - Finds all Twitch user matching a query
     *
     * @return View
     */
    public function index(): View
    {
        return view("web.twitch.games.index");
    }

    /**
     * GET - Shows a specific Twitch user
     *
     * @param Game $game
     * @return View
     */
    public function show(Game $game): View
    {
        /**
         * @var Collection $streams
         */
        $streams = $game->streams;

        $streams = $streams->mapToGroups(function ($item, $key) {
            return [$item['created_at']->startOfDay()->jsonSerialize()["date"] => $item];
        });

        return view("web.twitch.games.show", compact("game", "streams"));
    }

}
