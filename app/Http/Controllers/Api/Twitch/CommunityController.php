<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\CommunityRequest;
use App\Models\Twitch\Community;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * GET - Shows all Twitch communities
     * GET - Finds all Twitch communities matching a query
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $communities = Community::all();

        return ($request->has("with") ? $communities->load(explode(",", $request->with)) : $communities);
    }

    /**
     * POST - Stores the new Twitch community
     *
     * @param CommunityRequest $request
     * @return mixed
     */
    public function store(CommunityRequest $request)
    {
        $community = Community::create($request->validated());

        return $community;
    }

    /**
     * GET - Shows a specific Twitch community
     *
     * @param Community $community
     * @return Community
     */
    public function show(Community $community)
    {
        return $community;
    }

    /**
     * PATCH - Updates a specific Twitch community
     *
     * @param CommunityRequest $request
     * @param Community        $community
     * @return Community
     */
    public function update(CommunityRequest $request, Community $community)
    {
        $community->update($request->validated());

        return $community;
    }

    /**
     * DELETE - Deletes a specific Twitch community
     *
     * @param Community $community
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Community $community)
    {
        $community->delete();

        return response()->json("The community has been deleted");
    }
}
