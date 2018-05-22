<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TwitchEvent extends Model
{
	protected $fillable = [
		"id",
		"userid",
		"messageid",
		"type",
		"content",
		"created_at",
		"updated_at"
	];

	public function viewer()
	{
		return $this->BelongsTo(TwitchViewer::class, "userid");
	}


	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}
}
