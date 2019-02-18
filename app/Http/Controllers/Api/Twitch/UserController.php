<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\UserRequest;
use App\Models\Twitch\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * GET - Shows all Twitch users
     * GET - Finds all Twitch user matching a query
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $users = User::all();

        return ($request->has("with") ? $users->load(explode(",", $request->with)) : $users);
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
