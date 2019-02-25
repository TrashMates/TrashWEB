<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Filters\UserFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\UserRequest;
use App\Models\Twitch\Event;
use App\Models\Twitch\EventType;
use App\Models\Twitch\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use TiCubius\TwitchAPI\Facades\Users;

class UserController extends Controller
{
    /**
     * GET - Shows all Twitch users
     * GET - Finds all Twitch user matching a query
     *
     * @param Request     $request
     * @param UserFilters $filters
     * @return Collection
     */
    public function index(Request $request, UserFilters $filters): Collection
    {
        $users = User::filter($filters)->orderBy("username")->get();

        return ($request->has("with") ? $users->load(explode(",", $request->with)) : $users);
    }

    /**
     * POST - Fetches a specific user from the Twitch API
     *
     * @param Request $request
     */
    public function fetchUser(Request $request)
    {
        $request->validate([
            "id"       => "nullable|exists:users,id",
            "username" => "nullable|max:255",
        ]);

        if ($request->has("id")) {
            $user = Users::findFromId($request->id);
        } elseif ($request->has("username")) {
            $user = Users::findFromUsername($request->username);
        }

        User::updateOrCreate([
            "id" => $user->id,
        ], [
            "broadcaster_type"  => $user->broadcaster_type ?? null,
            "description"       => $user->description ?? null,
            "offline_image_url" => $user->offline_image_url ?? null,
            "profile_image_url" => $user->profile_image_url ?? null,
            "type"              => $user->type ?? null,
            "username"          => $user->display_name ?? $user->login,
        ]);
    }

    /**
     * GET - Fetches all followers of a Twitch User
     *
     * @param Request $request
     */
    public function fetchFollowers(Request $request)
    {
        $request->validate([
            "id" => "required|exists:users,id",
        ]);

        $eventType = EventType::where("name", "twitch/followed")->firstOrFail();

        // Fetch the user's followers
        $followers = Users::fetchFollowers($request->id);
        $followers_id = $followers->pluck("from_id");

        $followersInformations = Users::fetchFromId($followers_id->toArray());

        // Insert all followers
        foreach ($followersInformations as $follower) {
            User::updateOrCreate([
                "id" => $follower->id,
            ], [
                "broadcaster_type"  => $follower->broadcaster_type !== "" ? $follower->broadcaster_type : null,
                "description"       => $follower->description !== "" ? $follower->description : null,
                "offline_image_url" => $follower->offline_image_url !== "" ? $follower->offline_image_url : null,
                "profile_image_url" => $follower->profile_image_url !== "" ? $follower->profile_image_url : null,
                "type"              => $follower->type !== "" ? $follower->type : null,
                "username"          => $follower->display_name ?? $follower->login,
            ]);
        }

        //  Insert all followers events
        foreach ($followers as $follower) {
            // If the account was deleted, the follow link still
            // exists... somehow...?
            User::firstOrCreate([
                "id" => $follower->from_id,
            ], [
                "username" => $follower->from_name,
            ]);


            Event::updateOrCreate([
                "from_user_id"  => $follower->from_id,
                "to_user_id"    => $request->id,
                "event_type_id" => $eventType->id,
                "created_at"    => Carbon::parse($follower->followed_at)->format("Y-m-d H:i:s"),
            ]);
        }
    }

    /**
     * GET - Fetches all followings of a Twitch User
     *
     * @param Request $request
     */
    public function fetchFollowings(Request $request)
    {
        $request->validate([
            "id" => "required|exists:users,id",
        ]);

        $eventType = EventType::where("name", "twitch/followed")->firstOrFail();

        // Fetch all followings
        $followings = Users::fetchFollowings($request->id);
        $followings_id = $followings->pluck("to_id");

        $followingsInformation = Users::fetchFromId($followings_id->toArray());

        // Insert all followings
        foreach ($followingsInformation as $following) {
            User::updateOrCreate([
                "id" => $following->id,
            ], [
                "broadcaster_type"  => $following->broadcaster_type !== "" ? $following->broadcaster_type : null,
                "description"       => $following->description !== "" ? $following->description : null,
                "offline_image_url" => $following->offline_image_url !== "" ? $following->offline_image_url : null,
                "profile_image_url" => $following->profile_image_url !== "" ? $following->profile_image_url : null,
                "type"              => $following->type !== "" ? $following->type : null,
                "username"          => $following->display_name ?? $following->login,
            ]);
        }

        // Insert all followings events
        foreach ($followings as $following) {
            // If the account was deleted, the follow link still
            // exists... somehow...?
            $user = User::firstOrCreate([
                "id" => $following->to_id,
            ], [
                "username" => $following->to_name,
            ]);

            Event::updateOrCreate([
                "from_user_id"  => (string) $request->id,
                "to_user_id"    => $following->to_id,
                "event_type_id" => $eventType->id,
                "created_at"    => Carbon::parse($following->followed_at)->format("Y-m-d H:i:s"),
            ]);
        }
    }

    /**
     * POST - Stores the new Twitch user
     *
     * @param UserRequest $request
     * @return mixed
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return $user;
    }

    /**
     * GET - Shows a specific Twitch user
     *
     * @param User $user
     * @return User
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * PATCH - Updates a specific Twitch user
     *
     * @param UserRequest $request
     * @param User        $user
     * @return User
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return $user;
    }

    /**
     * DELETE - Deletes a specific Twitch user
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json("The user has been deleted");
    }
}
