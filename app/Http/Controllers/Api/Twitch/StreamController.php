<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Filters\StreamFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\StreamRequest;
use App\Jobs\FetchStreamsForGame;
use App\Models\Twitch\Game;
use App\Models\Twitch\Stream;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    /**
     * GET - Shows all Twitch streams
     * GET - Finds all Twitch stream matching a query
     *
     * @param Request      $request
     * @param StreamFilter $filter
     * @return Collection
     */
    public function index(Request $request, StreamFilter $filter): Collection
    {
        $streams = Stream::filter($filter)->orderBy("created_at")->get();

        return ($request->has("with") ? $streams->load(explode(",", $request->with)) : $streams);
    }

    /**
     * POST - Fetches a matching streams from the Twitch API
     *
     * @param Request $request
     */
    public function fetch(Request $request)
    {
        $request->validate([
            "game_id" => "required|exists:games,id",
        ]);

        FetchStreamsForGame::dispatch(Game::find($request->game_id));
    }

    /**
     * POST - Stores the new Twitch stream
     *
     * @param StreamRequest $request
     * @return mixed
     */
    public function store(StreamRequest $request)
    {
        $stream = Stream::create($request->validated());

        // Associating communities
        $communities = collect($request->communities);
        $stream->communities()->sync($communities->pluck('id'));

        return $stream;
    }

    /**
     * GET - Shows a specific Twitch stream
     *
     * @param Stream $stream
     * @return Stream
     */
    public function show(Stream $stream)
    {
        return $stream;
    }

    /**
     * PATCH - Updates a specific Twitch stream
     *
     * @param StreamRequest $request
     * @param Stream        $stream
     * @return Stream
     */
    public function update(StreamRequest $request, Stream $stream)
    {
        $stream->update($request->validated());

        return $stream;
    }

    /**
     * DELETE - Deletes a specific Twitch stream
     *
     * @param Stream $stream
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Stream $stream)
    {
        $stream->delete();

        return response()->json("The stream has been deleted");
    }
}
