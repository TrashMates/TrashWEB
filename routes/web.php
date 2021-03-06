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

Route::get("/", "WebController@index")->name("web.index");

Route::group(["prefix" => "twitch", "as" => "twitch.", "namespace" => "Twitch"], function () {

    Route::resource("games", "GameController");
    Route::resource("users", "UserController");
    Route::resource("streams", "StreamController");

});
