<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\StreamRequest;
use App\Models\Twitch\Stream;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    /**
     * RECURSIVE - Fetches all streams of a Twitch game
     *
     * @param string                              $gameid
     * @param \Illuminate\Support\Collection|null $streams
     * @param string|null                         $pagination
     * @return \Illuminate\Support\Collection
     */
    private function _fetchStreams(string $gameid, \Illuminate\Support\Collection $streams = null, string $pagination = null): \Illuminate\Support\Collection
    {
        if ($streams === null) {
            $streams = collect();
        }

        $client = new Client();
        $response = $client->get("https://api.twitch.tv/helix/streams?game_id={$gameid}&first=100&after={$pagination}", [
            "headers" => ["Client-ID" => env("TWITCH_CLIENT_ID")],
        ]);

        $data = json_decode($response->getBody()->getContents());
        $foundStreams = $data->data;

        foreach ($foundStreams as $stream) {
            $streams->push($stream);
        }

        if (count($streams) >= 100) {
            $pagination = $data->pagination->cursor;
            return $this->_fetchFollowers($gameid, $streams, $pagination);
        }

        return $streams;
    }

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
     * POST - Fetches a matching streams from the Twitch API
     *
     * @param Request $request
     */
    public function fetch(Request $request)
    {
        $request->validate([
            "game_id" => "required|exists:games,id",
        ]);

        $streams = $this->_fetchStreams($request->game_id);

        foreach ($streams as $stream) {
            Stream::updateOrCreate([
                "id"      => $stream->id,
                "game_id" => $stream->game_id,
                "user_id" => $stream->user_id,
            ], [
                "language"   => $stream->language,
                "title"      => $stream->title,
                "type"       => $stream->type,
                "created_at" => Carbon::parse($stream->started_at)->format("Y-m-d H:i:s"),
            ]);
        }

        $expiredStreams = Stream::where("game_id", $request->game_id)->whereNull("stopped_at")->whereNotIn("id", $streams->pluck("id"))->get();
        $expiredStreams->each(function (Stream $stream) {
            $stream->update([
                "stopped_at" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        });
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
