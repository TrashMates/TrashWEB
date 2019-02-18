<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\EventTypeRequest;
use App\Models\Twitch\EventType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    /**
     * GET - Shows all Twitch event types
     * GET - Finds all Twitch event types matching a query
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $eventTypes = EventType::all();

        return ($request->has("with") ? $eventTypes->load(explode(",", $request->with)) : $eventTypes);
    }

    /**
     * POST - Stores the new Twitch event type
     *
     * @param EventTypeRequest $request
     * @return mixed
     */
    public function store(EventTypeRequest $request)
    {
        $eventType = EventType::create($request->validated());

        return $eventType;
    }

    /**
     * GET - Shows a specific Twitch event type
     *
     * @param EventType $eventType
     * @return EventType
     */
    public function show(EventType $eventType)
    {
        return $eventType;
    }

    /**
     * PATCH - Updates a specific Twitch event type
     *
     * @param EventTypeRequest $request
     * @param EventType        $eventType
     * @return EventType
     */
    public function update(EventTypeRequest $request, EventType $eventType)
    {
        $eventType->update($request->validated());

        return $eventType;
    }

    /**
     * DELETE - Deletes a specific Twitch event type
     *
     * @param EventType $eventType
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(EventType $eventType)
    {
        $eventType->delete();

        return response()->json("The event type has been deleted");
    }
}
