<?php

namespace App\Http\Controllers\Twitch;

use App\Http\Controllers\Controller;
use App\Models\Twitch\User;
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
        /**
         * @var Collection $events
         */
        $events = $user->eventsReceiver;

        $events = $events->mapToGroups(function ($item, $key) {
            return [$item['created_at']->startOfDay()->jsonSerialize()["date"] => $item];
        });

        return view("web.twitch.users.show", compact("events", "user"));
    }

}
