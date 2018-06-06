<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordEvent extends Model
{
	protected $fillable = [
		"id",
		"viewer_id",
		"message_id",
		"type",
		"content",
		"created_at",
		"updated_at",
	];

	public function viewer()
	{
		return $this->BelongsTo(DiscordViewer::class);
	}


	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")
			->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}
}
