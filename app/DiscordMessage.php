<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordMessage extends Model
{
	public $incrementing = false;
	protected $fillable = [
		"id",
		"viewer_id",
		"channel",
		"content",
		"created_at",
		"updated_at",
	];

	public function viewer()
	{
		return $this->BelongsTo(DiscordViewer::class);
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
