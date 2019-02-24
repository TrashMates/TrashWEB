<?php

use Illuminate\Database\Seeder;

class EventTypesSeeder extends Seeder
{
    private $types = [
        "twitch/chat/banned",
        "twitch/chat/cleared",
        "twitch/cheered",
        "twitch/followed",
        "twitch/hosted",
        "twitch/subscribed",
        "twitch/raided",
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->types as $type) {
            \App\Models\Twitch\EventType::create([
                "name" => $type,
            ]);
        }
    }
}
