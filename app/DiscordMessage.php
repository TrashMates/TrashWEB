<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DiscordMessage extends Model
{
	public $incrementing = false;
	protected $fillable = [
		"id",
		"userid",
		"channel",
		"content",
		"created_at",
		"updated_at"
	];

	public function viewer()
	{
		return $this->BelongsTo(DiscordViewer::class, "userid");
	}

	public function events()
	{
		return $this->hasMany(DiscordEvent::class, "messageid");
	}


	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}
}
