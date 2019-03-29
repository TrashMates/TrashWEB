<?php

namespace TiCubius\TwitchAPI;

use Illuminate\Support\ServiceProvider;
use TiCubius\TwitchAPI\API\Games;
use TiCubius\TwitchAPI\API\Users;

class TwitchAPIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishes([
            __DIR__ . "/config" => base_path("config"),
        ]);


        \App::bind("games", function () {
            return new Games;
        });
        
        \App::bind("users", function () {
            return new Users;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
