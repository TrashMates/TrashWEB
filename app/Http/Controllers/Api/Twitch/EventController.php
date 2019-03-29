<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\EventRequest;
use App\Models\Twitch\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * GET - Shows all Twitch events
     * GET - Finds all Twitch event matching a query
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $events = Event::all();

        return ($request->has("with") ? $events->load(explode(",", $request->with)) : $events);
    }

    /**
     * POST - Stores the new Twitch event
     *
     * @param EventRequest $request
     * @return mixed
     */
    public function store(EventRequest $request)
    {
        $event = Event::create($request->validated());

        return $event;
    }

    /**
     * GET - Shows a specific Twitch event
     *
     * @param Event $event
     * @return Event
     */
    public function show(Event $event)
    {
        return $event;
    }

    /**
     * PATCH - Updates a specific Twitch event
     *
     * @param EventRequest $request
     * @param Event        $event
     * @return Event
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->update($request->validated());

        return $event;
    }

    /**
     * DELETE - Deletes a specific Twitch event
     *
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json("The event has been deleted");
    }
}
