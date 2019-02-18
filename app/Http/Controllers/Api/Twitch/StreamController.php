<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\StreamRequest;
use App\Models\Twitch\Stream;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    /**
     * GET - Shows all Twitch streams
     * GET - Finds all Twitch stream matching a query
     *
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $streams = Stream::all();

        return ($request->has("with") ? $streams->load(explode(",", $request->with)) : $streams);
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
