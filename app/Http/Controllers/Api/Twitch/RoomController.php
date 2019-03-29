<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\RoomRequest;
use App\Models\Twitch\Room;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * GET - Shows all Twitch rooms
     * GET - Finds all Twitch room matching a query
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $rooms = Room::all();

        return ($request->has("with") ? $rooms->load(explode(",", $request->with)) : $rooms);
    }

    /**
     * POST - Stores the new Twitch room
     *
     * @param RoomRequest $request
     * @return mixed
     */
    public function store(RoomRequest $request)
    {
        $room = Room::create($request->validated());

        return $room;
    }

    /**
     * GET - Shows a specific Twitch room
     *
     * @param Room $room
     * @return Room
     */
    public function show(Room $room)
    {
        return $room;
    }

    /**
     * PATCH - Updates a specific Twitch room
     *
     * @param RoomRequest $request
     * @param Room        $room
     * @return Room
     */
    public function update(RoomRequest $request, Room $room)
    {
        $room->update($request->validated());

        return $room;
    }

    /**
     * DELETE - Deletes a specific Twitch room
     *
     * @param Room $room
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return response()->json("The room has been deleted");
    }
}
