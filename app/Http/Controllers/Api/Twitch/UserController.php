<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Filters\UserFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\UserRequest;
use App\Models\Twitch\Event;
use App\Models\Twitch\EventType;
use App\Models\Twitch\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * RECURSIVE - Fetches upto 1000 followers of a Twitch user
     *
     * @param string                              $userid
     * @param \Illuminate\Support\Collection|null $followers
     * @param string|null                         $pagination
     * @return \Illuminate\Support\Collection
     */
    private function _fetchFollowers(string $userid, \Illuminate\Support\Collection $followers = null, string $pagination = null): \Illuminate\Support\Collection
    {
        if ($followers === null) {
            $followers = collect();
        }

        $client = new Client();
        $response = $client->get("https://api.twitch.tv/helix/users/follows?to_id={$userid}&first=100&after={$pagination}", [
            "headers" => ["Client-ID" => env("TWITCH_CLIENT_ID")],
        ]);

        $data = json_decode($response->getBody()->getContents());
        $users = $data->data;

        foreach ($users as $user) {
            $followers->push($user);
        }

        if ((count($users) >= 100) && $followers->count() < 1000) {
            $pagination = $data->pagination->cursor;
            return $this->_fetchFollowers($userid, $followers, $pagination);
        }

        return $followers;
    }

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
    public function fetch(Request $request)
    {
        $eventType = EventType::where("name", "twitch/followed")->firstOrFail();

        $request->validate([
            "username" => "required|max:255",
        ]);

        $client = new Client();
        $response = $client->get("https://api.twitch.tv/helix/users?login={$request->username}", [
            "headers" => ["Client-ID" => env("TWITCH_CLIENT_ID")],
        ]);

        $foundUsers = json_decode($response->getBody()->getContents())->data;
        foreach ($foundUsers as $user) {
            User::updateOrCreate([
                "id"                => $user->id,
                "broadcaster_type"  => $user->broadcaster_type,
                "description"       => $user->description,
                "offline_image_url" => $user->offline_image_url,
                "profile_image_url" => $user->profile_image_url,
                "type"              => $user->type,
                "username"          => $user->display_name ?? $user->login,
            ]);

            $followers = $this->_fetchFollowers($user->id);
            foreach ($followers as $follower) {
                $createdFollower = User::updateOrCreate([
                    "id"       => $follower->from_id,
                    "username" => $follower->from_name,
                ]);


                Event::updateOrCreate([
                    "from_user_id"  => $createdFollower->id,
                    "to_user_id"    => $user->id,
                    "event_type_id" => $eventType->id,
                    "created_at"    => Carbon::parse($follower->followed_at)->format("Y-m-d H:i:s"),
                ]);
            }
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
