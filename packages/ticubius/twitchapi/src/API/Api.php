<?php

namespace TiCubius\TwitchAPI\API;

use GuzzleHttp\Client;
use TiCubius\TwitchAPI\Exceptions\ClientIdMissingException;
use TiCubius\TwitchAPI\Exceptions\ClientSecretMissingException;

class Api
{
    /**
     * @var string $apiUrl
     */
    protected $apiUrl = "https://api.twitch.tv/helix";

    /**
     * @var string $token
     */
    protected $bearerToken;

    /**
     * @var Client Client
     */
    protected $client;

    /**
     * @var string $clientId
     */
    protected $clientId;

    /**
     * @var string $clientSecret
     */
    protected $clientSecret;

    /**
     * @var array $headers
     */
    protected $headers;


    /**
     * API constructor
     *
     * @param string|null $clientId
     * @param string|null $clientSecret
     * @throws ClientIdMissingException
     * @throws ClientSecretMissingException
     */
    public function __construct(?string $clientId = null, ?string $clientSecret = null)
    {
        $this->client = new Client();

        if ($clientId) {
            $this->clientId = $clientId;
        } elseif (config("twitchapi.client_id")) {
            $this->clientId = config("twitchapi.client_id");
        } else {
            throw new ClientIdMissingException;
        }

        if ($clientSecret) {
            $this->clientSecret = $clientSecret;
        } elseif (config("twitchapi.client_secret")) {
            $this->clientSecret = config("twitchapi.client_secret");
        } else {
            throw new ClientSecretMissingException;
        }

        if (!$this->bearerToken) {
            $url = "https://id.twitch.tv/oauth2/token?client_id={$this->clientId}&client_secret={$this->clientSecret}&grant_type=client_credentials";
            $response = $this->client->post($url);


            $this->bearerToken = (json_decode($response->getBody()->getContents())->access_token);
        }

        $this->headers = ["Authorization" => "Bearer {$this->bearerToken}"];
    }
}
