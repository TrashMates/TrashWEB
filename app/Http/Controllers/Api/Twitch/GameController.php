<?php

namespace App\Http\Controllers\Api\Twitch;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twitch\GameRequest;
use App\Models\Twitch\Game;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * GET - Shows all Twitch games
     * GET - Finds all Twitch games matching a query
     *
     * @param Request $request &
     * @return Collection
     */
    public function index(Request $request): Collection
    {
        $games = Game::all();

        return ($request->has("with") ? $games->load(explode(",", $request->with)) : $games);
    }

    /**
     * POST - Stores the new Twitch game
     *
     * @param GameRequest $request
     * @return mixed
     */
    public function store(GameRequest $request)
    {
        $game = Game::create($request->validated());

        return $game;
    }

    /**
     * GET - Shows a specific Twitch game
     *
     * @param Game $game
     * @return Game
     */
    public function show(Game $game)
    {
        return $game;
    }

    /**
     * PATCH - Updates a specific Twitch game
     *
     * @param GameRequest $request
     * @param Game        $game
     * @return Game
     */
    public function update(GameRequest $request, Game $game)
    {
        $game->update($request->validated());

        return $game;
    }

    /**
     * DELETE - Deletes a specific Twitch game
     *
     * @param Game $game
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Game $game)
    {
        $game->delete();

        return response()->json("The game has been deleted");
    }
}
