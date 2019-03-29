<?php

namespace TiCubius\TwitchAPI\API;

use Illuminate\Support\Collection;

class Games extends Api
{
    /**
     * Fetches a Twitch Game's information using its ID
     *
     * @param string $game_id
     * @return object
     * @throws \Exception
     */
    public function findFromId(string $game_id): object
    {
        try {
            $game = json_decode($this->client->get("{$this->apiUrl}/games?id={$game_id}", [
                "headers" => $this->headers,
            ])->getBody()->getContents())->data;

            if (count($game) === 1) {
                return $game[0];
            } else {
                throw new \Exception("No game was found with this game_id: {$game_id}");
            }
        } catch (\Exception $e) {
            throw new \Exception("An error has occurred during the request: {$e->getMessage()}");
        }
    }

    /**
     * Fetches a Twitch Game's information using its name
     *
     * @param string $name
     * @return object
     * @throws \Exception
     */
    public function findFromName(string $name): object
    {
        try {
            $name = json_decode($this->client->get("{$this->apiUrl}/games?name={$name}", [
                "headers" => $this->headers,
            ])->getBody()->getContents())->data;

            if (count($name) === 1) {
                return $name[0];
            } else {
                throw new \Exception("No game was found with this name");
            }
        } catch (\Exception $e) {
            throw new \Exception("An error has occurred during the request: {$e->getMessage()}");
        }
    }


    /**
     * Fetches multiples Twitch Games' information using their id
     *
     * @param array $games_id
     * @return Collection
     */
    public function fetchFromId(array $games_id): Collection
    {
        $games = [];

        do {
            $query = join("&id=", array_slice($games_id, 0, 100));
            $games_id = array_slice($games_id, 100);

            $response = $this->client->get("{$this->apiUrl}/games?id=${query}", [
                "headers" => $this->headers,
            ]);

            $games = array_merge($games, json_decode($response->getBody()->getContents())->data);

        } while (count($games_id) >= 1);

        return collect($games);
    }

    /**
     * Fetches multiples Twitch Games' information using their names
     *
     * @param array $names
     * @return Collection
     */
    public function fetchFromUsername(array $names): Collection
    {
        $games = [];

        do {
            $query = join("&name=", array_slice($names, 0, 100));
            $names = array_slice($names, 100);

            $response = $this->client->get("{$this->apiUrl}/games?names=${query}", [
                "headers" => $this->headers,
            ]);

            $games = array_merge($games, json_decode($response->getBody()->getContents())->data);

        } while (count($names) >= 1);

        return collect($games);
    }


    /**
     * Fetches all streams for the Twitch Game
     *
     * @param string $game_id
     * @param array  $streams
     * @param string $pagination
     * @return Collection
     */
    public function fetchStreams(string $game_id, array $streams = [], string $pagination = ""): Collection
    {
        $response = $this->client->get("{$this->apiUrl}/streams?game_id={$game_id}&first=100&after={$pagination}", [
            "headers" => $this->headers,
        ]);

        $data = json_decode($response->getBody()->getContents());
        $streams = array_merge($streams, $data->data);

        if (count($data->data) === 100) {
            return $this->fetchStreams($game_id, $streams, $data->pagination->cursor);
        }

        return collect($streams);
    }
}
