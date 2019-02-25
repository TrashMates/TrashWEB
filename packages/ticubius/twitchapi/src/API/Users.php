<?php
/**
 * Created by IntelliJ IDEA.
 * User: ticubius
 * Date: 24/02/19
 * Time: 23:18
 */

namespace TiCubius\TwitchAPI\API;

use Illuminate\Support\Collection;

class Users extends Api
{
    /**
     * Fetches a Twitch User's information using its ID
     *
     * @param string $user_id
     * @return object
     * @throws \Exception
     */
    public function findFromId(string $user_id): object
    {
        try {
            $user = json_decode($this->client->get("{$this->apiUrl}/users?id={$user_id}", [
                "headers" => $this->headers,
            ])->getBody()->getContents())->data;

            if (count($user) === 1) {
                return $user[0];
            } else {
                throw new \Exception("No user was found with this user_id: {$user_id}");
            }
        } catch (\Exception $e) {
            throw new \Exception("An error has occurred during the request: {$e->getMessage()}");
        }
    }

    /**
     * Fetches a Twitch User's information using its username
     *
     * @param string $username
     * @return object
     * @throws \Exception
     */
    public function findFromUsername(string $username): object
    {
        try {
            $user = json_decode($this->client->get("{$this->apiUrl}/users?login={$username}", [
                "headers" => $this->headers,
            ])->getBody()->getContents())->data;

            if (count($user) === 1) {
                return $user[0];
            } else {
                throw new \Exception("No user was found with this username");
            }
        } catch (\Exception $e) {
            throw new \Exception("An error has occurred during the request: {$e->getMessage()}");
        }
    }


    /**
     * Fetches multiples Twitch Users' information using their ID
     *
     * @param array $users_id
     * @return Collection
     */
    public function fetchFromId(array $users_id): Collection
    {
        $users = [];

        do {
            $query = join("&id=", array_slice($users_id, 0, 100));
            $users_id = array_slice($users_id, 100);

            $response = $this->client->get("{$this->apiUrl}/users?id=${query}", [
                "headers" => $this->headers,
            ]);

            $users = array_merge($users, json_decode($response->getBody()->getContents())->data);

        } while (count($users_id) >= 1);

        return collect($users);
    }

    /**
     * Fetches multiples Twitch Users' information using their usernames
     *
     * @param array $usernames
     * @return Collection
     */
    public function fetchFromUsername(array $usernames): Collection
    {
        $users = [];

        do {
            $query = join("&login=", array_slice($usernames, 0, 100));
            $usernames = array_slice($usernames, 100);

            $response = $this->client->get("{$this->apiUrl}/users?login=${query}", [
                "headers" => $this->headers,
            ]);

            $users = array_merge($users, json_decode($response->getBody()->getContents())->data);

        } while (count($usernames) >= 1);

        return collect($users);
    }


    /**
     * Fetches all Twitch User's followers
     *
     * @param string $user_id
     * @param array  $followers
     * @param string $pagination
     * @return Collection
     */
    public function fetchFollowers(string $user_id, array $followers = [], string $pagination = ""): Collection
    {
        $response = $this->client->get("{$this->apiUrl}/users/follows?to_id={$user_id}&first=100&after={$pagination}", [
            "headers" => $this->headers,
        ]);

        $data = json_decode($response->getBody()->getContents());
        $followers = array_merge($followers, $data->data);

        if ((count($data->data) === 100) && count($followers) < 10000) {
            return $this->fetchFollowers($user_id, $followers, $data->pagination->cursor);
        }

        return collect($followers);
    }


    /**
     * Fetches all Twitch User's followings
     *
     * @param string $user_id
     * @param array  $followers
     * @param string $pagination
     * @return Collection
     */
    public function fetchFollowings(string $user_id, array $followers = [], string $pagination = ""): Collection
    {
        $response = $this->client->get("{$this->apiUrl}/users/follows?from_id={$user_id}&first=100&after={$pagination}", [
            "headers" => $this->headers,
        ]);

        $data = json_decode($response->getBody()->getContents());
        $followers = array_merge($followers, $data->data);

        if ((count($data->data) === 100) && count($followers) < 10000) {
            return $this->fetchFollowings($user_id, $followers, $data->pagination->cursor);
        }

        return collect($followers);
    }
}
