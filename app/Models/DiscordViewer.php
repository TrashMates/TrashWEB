<?php

namespace App\Models;

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

	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")
			->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}

	public function messages()
	{
		return $this->hasMany(DiscordMessage::class, 'viewer_id');
	}

	public function events()
	{
		return $this->hasMany(DiscordEvent::class, 'viewer_id');
	}
}
