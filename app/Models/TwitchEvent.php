<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwitchEvent extends Model
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

	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")->selectRaw("DATE(created_at) AS date")->groupBy("date")->orderBy("date", "ASC");
	}

	public function viewer()
	{
		return $this->BelongsTo(TwitchViewer::class, 'viewer_id');
	}
}
