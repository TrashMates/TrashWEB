<?php

namespace App\Http\Controllers\Twitch;

use App\Http\Controllers\Controller;
use App\Models\Twitch\User;
use Carbon\Carbon;
use Illuminate\View\View;

class UserController extends Controller
{

    /**
     * GET - Shows all Twitch users
     * GET - Finds all Twitch user matching a query
     *
     * @return View
     */
    public function index(): View
    {
        return view("web.twitch.users.index");
    }

    /**
     * GET - Shows a specific Twitch user
     *
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        $user->load("streams.game");

        /**
         * FETCH ALL FOLLOWERS
         * IN THE LAST 2 MONTHS
         */
        $followers = $user->eventsReceiver()->whereHas("type", function ($query) {
            return $query->where("name", "twitch/followed");
        })->where("events.created_at", ">", Carbon::now()->subMonth(2))->get();

        $followers = $followers->mapToGroups(function ($item, $key) {
            return [$item["created_at"]->format("Y-m-d") => $item];
        });


        /**
         * FETCH ALL FOLLOWINGS
         * IN THE LAST 2 MONTHS
         */
        $followings = $user->eventsAuthor()->whereHas("type", function ($query) {
            return $query->where("name", "twitch/followed");
        })->where("events.created_at", ">", Carbon::now()->subMonth(2))->get();

        $followings = $followings->mapToGroups(function ($item, $key) {
            return [$item["created_at"]->format("Y-m-d") => $item];
        });


        return view("web.twitch.users.show", compact("followers", "followings", "user"));
    }

}
