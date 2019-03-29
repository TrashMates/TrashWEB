<?php

namespace TiCubius\TwitchAPI\Facades;

use Illuminate\Support\Facades\Facade;

class Games extends Facade
{
    /**
     * Facade Accessor
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return "games";
    }

}
