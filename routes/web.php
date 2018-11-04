<?php

use Illuminate\Support\Facades\Route;

// "middleware" => "web" because we removed it from the RouteServiceProvider
Route::group(["prefix" => "admin", "middleware" => "web"], function () {

    Route::get("/login", "AdminController@loginForm")->name("admin.login");
    Route::post("/login", "AdminController@login");

    Route::get("/logoff", "AdminController@logoff")->name("admin.logoff");

});

// "middleware" => "web" because we removed it from the RouteServiceProvider
// "middleware" => "auth" because we need a Logged In User for theses pages
Route::group(["prefix" => "admin", "middleware" => ["web", "auth"], "as" => "admin."], function () {

    Route::get("/", "AdminController@index")->name("index");

	Route::group(["prefix" => "/twitch", "as" => "twitch."], function () {
		Route::resource("events", "TwitchEventController")->only(["index", "show"]);
		Route::resource("messages", "TwitchMessageController")->only(["index", "show"]);
		Route::resource("viewers", "TwitchViewerController")->only(["index", "show"]);

	});

	Route::group(["prefix" => "/discord", "as" => "discord."], function () {
		Route::resource("events", "DiscordEventController")->only(["index", "show"]);
		Route::resource("messages", "DiscordMessageController")->only(["index", "show"]);
		Route::resource("viewers", "DiscordViewerController")->only(["index", "show"]);

	});
});

Route::group(["middleware" => "web"], function () {

    Route::get("/", function () {return view("trashmates.index");});

});
