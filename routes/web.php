<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// "middleware" => "web" because we removed it from the RouteServiceProvider
Route::group(["domain" => "admin." . env("APP_URL"), "middleware" => "web"], function() {

	Route::get("/login", "AdminController@loginForm")->name("admin.login");
	Route::post("/login", "AdminController@login");

	Route::get("/logoff", "AdminController@logoff")->name("admin.logoff");

});

Route::group(["domain" => "admin." . env("APP_URL"), "middleware" => ["web", "auth"]], function() {

	Route::get("/", "AdminController@index")->name("admin.index");


	/**
	 * DISCORD ROUTES: Events
	 */
	Route::get("discord/events", "DiscordEventController@index")->name("admin.discord.events");
	Route::get("discord/events/{eventID}", "DiscordEventController@show");

	/**
	 * DISCORD ROUTES: Messages
	 */
	Route::get("discord/messages", "DiscordMessageController@index")->name("admin.discord.messages");
	Route::get("discord/messages/{messageID}", "DiscordMessageController@show");
	/**
	 * DISCORD ROUTES: Viewers
	 */
	Route::get("discord/viewers", "DiscordViewerController@index")->name("admin.discord.viewers");
	Route::get("discord/viewers/{discordID}", "DiscordViewerController@show");



	/**
	 * TWITCH ROUTES: Events
	 */
	Route::get("twitch/events", "TwitchEventController@index")->name("admin.twitch.events");
	Route::get("twitch/events/{eventID}", "TwitchEventController@show");

	/**
	 * TWITCH ROUTES: Messages
	 */
	Route::get("twitch/messages", "TwitchMessageController@index")->name("admin.twitch.messages");
	Route::get("twitch/messages/{messageID}", "TwitchMessageController@show");

	/**
	 * TWITCH ROUTES: Viewers
	 */
	Route::get("twitch/viewers", "TwitchViewerController@index")->name("admin.twitch.viewers");
	Route::get("twitch/viewers/{twitchID}", "TwitchViewerController@show");


	/**
	 * STREAMER TOOLS
	 */
	Route::get("tools/game", "ToolController@game")->name("admin.tool.game");

});