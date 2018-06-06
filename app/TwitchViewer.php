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
		"updated_at",
	];

	public function messages()
	{
		return $this->hasMany(TwitchMessage::class);
	}

	public function events()
	{
		return $this->hasMany(TwitchEvent::class);
	}

	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")
			->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}
}
