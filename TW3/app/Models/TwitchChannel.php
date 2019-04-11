<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwitchChannel extends Model
{
	public $timestamps = false;

	protected $fillable = ["id", "username", "description", "type", "picture"];
}
