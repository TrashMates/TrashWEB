<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TwitchViewer extends Model
{
	public $incrementing = false;
	protected $fillable = [
		"id",
		"username",
		"role",
		"created_at",
		"updated_at"
	];

	public function messages()
	{
		return $this->hasMany(TwitchMessage::class, "userid");
	}

	public function events()
	{
		return $this->hasMany(TwitchEvent::class, "userid");
	}
}
