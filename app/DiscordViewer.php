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
		"updated_at",
	];

	public function messages()
	{
		return $this->hasMany(DiscordMessage::class);
	}

	public function events()
	{
		return $this->hasMany(DiscordEvent::class);
	}


	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")
			->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}
}
