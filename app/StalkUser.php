<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StalkUser extends Model
{
    public $table = "twitch_stalker_users";
    protected $fillable = ["id", "username"];
    public $timestamps = false;


    public function followings()
    {
        return $this->hasMany(StalkFollowing::class, "follow_from_id", "id");
    }

    public function followers()
    {
        return $this->hasMany(StalkFollowing::class, "follow_to_id", "id");
    }
}
