<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordViewer extends Model
{
	public $incrementing = false;
	protected $fillable = [
		"id",
		"username",
		"discriminator",
		"role",
		"created_at",
		"updated_at"
	];

	public function messages()
	{
		return $this->hasMany(DiscordMessage::class, "userid");
	}

	public function events()
	{
		return $this->hasMany(DiscordEvent::class, "userid");
	}
}
