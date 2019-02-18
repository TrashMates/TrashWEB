<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\MessageRequest;
use App\Models\Twitch\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * GET - Shows all Twitch messages
     * GET - Finds all Twitch message matching a query
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $messages = Message::all();

        return ($request->has("with") ? $messages->load(explode(",", $request->with)) : $messages);
    }

    /**
     * POST - Stores the new Twitch message
     *
     * @param MessageRequest $request
     * @return mixed
     */
    public function store(MessageRequest $request)
    {
        $message = Message::create($request->validated());

        return $message;
    }

    /**
     * GET - Shows a specific Twitch message
     *
     * @param Message $message
     * @return Message
     */
    public function show(Message $message)
    {
        return $message;
    }

    /**
     * PATCH - Updates a specific Twitch message
     *
     * @param MessageRequest $request
     * @param Message        $message
     * @return Message
     */
    public function update(MessageRequest $request, Message $message)
    {
        $message->update($request->validated());

        return $message;
    }

    /**
     * DELETE - Deletes a specific Twitch message
     *
     * @param Message $message
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return response()->json("The message has been deleted");
    }
}
