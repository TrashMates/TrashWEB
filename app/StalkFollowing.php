<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StalkFollowing extends Model
{
    public $table = "twitch_stalker_following";
    protected $fillable = ["follow_from_id", "follow_to_id", "followed_at"];
    public $timestamps = false;
}
