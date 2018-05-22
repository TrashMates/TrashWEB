<?php

use Illuminate\Http\Request;

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
Route::group(["domain" => "api." . env("APP_URL")], function() {

	Route::get("stats/twitch/events", "TwitchEventController@stats");
	Route::get("stats/twitch/messages", "TwitchMessageController@stats");
	Route::get("stats/twitch/viewers", "TwitchViewerController@stats");

	Route::get("stats/discord/events", "DiscordEventController@stats");
	Route::get("stats/discord/messages", "DiscordMessageController@stats");
	Route::get("stats/discord/viewers", "DiscordViewerController@stats");
});

// "middleware" => "api" because we removed it from the RouteServiceProvider
Route::group(["domain" => "api." . env("APP_URL"), "middleware" => "api"], function() {

	/**
	 * TWITCH ROUTES: Events
	 */
	Route::get("twitch/events", "TwitchEventController@index");
	Route::get("twitch/events/{eventID}", "TwitchEventController@show");
	Route::post("twitch/events", "TwitchEventController@store");
	Route::patch("twitch/events/{eventID}", "TwitchEventController@update");

	/**
	 * TWITCH ROUTES: Messages
	 */
	Route::get("twitch/messages", "TwitchMessageController@index");
	Route::get("twitch/messages/{messageID}", "TwitchMessageController@show");
	Route::post("twitch/messages", "TwitchMessageController@store");
	Route::patch("twitch/messages/{messageID}", "TwitchMessageController@update");

	/**
	 * TWITCH ROUTES: Viewers
	 */
	Route::get("twitch/viewers", "TwitchViewerController@index");
	Route::get("twitch/viewers/{twitchID}", "TwitchViewerController@show");
	Route::post("twitch/viewers", "TwitchViewerController@store");
	Route::patch("twitch/viewers/{twitchID}", "TwitchViewerController@update");



	/**
	 * DISCORD ROUTES: Events
	 */
	Route::get("discord/events", "DiscordEventController@index");
	Route::get("discord/events/{eventID}", "DiscordEventController@show");
	Route::post("discord/events", "DiscordEventController@store");
	Route::patch("discord/events/{eventID}", "DiscordEventController@update");

	/**
	 * DISCORD ROUTES: Messages
	 */
	Route::get("discord/messages", "DiscordMessageController@index");
	Route::get("discord/messages/{messageID}", "DiscordMessageController@show");
	Route::post("discord/messages", "DiscordMessageController@store");
	Route::patch("discord/messages/{messageID}", "DiscordMessageController@update");

	/**
	 * DISCORD ROUTES: Viewers
	 */
	Route::get("discord/viewers", "DiscordViewerController@index");
	Route::get("discord/viewers/{discordID}", "DiscordViewerController@show");
	Route::post("discord/viewers", "DiscordViewerController@store");
	Route::patch("discord/viewers/{discordID}", "DiscordViewerController@update");

});