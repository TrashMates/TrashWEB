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

// We don't want the Middleware here
Route::group(["prefix" => "api"], function () {

    Route::get("stats/twitch/events", "TwitchEventController@stats");
    Route::get("stats/twitch/messages", "TwitchMessageController@stats");
    Route::get("stats/twitch/viewers", "TwitchViewerController@stats");

    Route::get("stats/discord/events", "DiscordEventController@stats");
    Route::get("stats/discord/messages", "DiscordMessageController@stats");
    Route::get("stats/discord/viewers", "DiscordViewerController@stats");

});

// "middleware" => "api" because we removed it from the RouteServiceProvider
Route::group(["prefix" => "/api", "middleware" => "api"], function () {

	Route::group(["prefix" => "/twitch"], function () {
		Route::resource("events", "TwitchEventController")->only(["index", "show", "store"]);
		Route::resource("messages", "TwitchMessageController")->only(["index", "show", "store", "update"]);
		Route::resource("viewers", "TwitchViewerController")->only(["index", "show", "store", "update"]);

	});

	Route::group(["prefix" => "/discord"], function () {
		Route::resource("events", "DiscordEventController")->only(["index", "show", "store"]);
		Route::resource("messages", "DiscordMessageController")->only(["index", "show", "store", "update"]);
		Route::resource("viewers", "DiscordViewerController")->only(["index", "show", "store", "update"]);

	});

});
