<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TwitchMessage extends Model
{
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
		return $this->BelongsTo(TwitchViewer::class, "userid");
	}

	public function events()
	{
		return $this->hasMany(TwitchEvent::class, "messageid");
	}


	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}
}
