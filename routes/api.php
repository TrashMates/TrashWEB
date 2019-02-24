<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(["prefix" => "twitch", "as" => "twitch.", "namespace" => "Api\Twitch"], function () {
    Route::resource("communities", "CommunityController");
    Route::resource("events", "EventController");
    Route::resource("event-types", "EventTypeController");

    Route::resource("games", "GameController");
    Route::post("games/fetch", "GameController@fetch");

    Route::resource("rooms", "RoomController");

    Route::resource("streams", "StreamController");
    Route::post("streams/fetch", "StreamController@fetch");

    Route::resource("users", "UserController");
    Route::post("users/fetch", "UserController@fetch");
});

