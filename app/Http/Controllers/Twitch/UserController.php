<?php

namespace App\Http\Controllers\Twitch;

use App\Http\Controllers\Controller;
use App\Models\Twitch\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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
        $followers = $user->eventsReceiver()->whereDate("created_at", ">", Carbon::now()->subMonth(2))->get();
        $followings = $user->eventsAuthor()->whereDate("created_at", ">", Carbon::now()->subMonth(2))->get();

        $followers = $followers->mapToGroups(function ($item, $key) {
            return [$item['created_at']->startOfDay()->jsonSerialize()["date"] => $item];
        });
        $followings = $followings->mapToGroups(function ($item, $key) {
            return [$item['created_at']->startOfDay()->jsonSerialize()["date"] => $item];
        });

        return view("web.twitch.users.show", compact("followers", "followings", "user"));
    }

}
