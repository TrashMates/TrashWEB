<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TwitchViewer extends Model
{
	public $incrementing = false;
	protected $fillable = [
		"id",
		"username",
		"role",
		"created_at",
		"updated_at"
	];

	public function messages()
	{
		return $this->hasMany(TwitchMessage::class, "userid");
	}

	public function events()
	{
		return $this->hasMany(TwitchEvent::class, "userid");
	}


	public static function getStats()
	{
		// return self::select(DB::raw('YEAR(created_at) AS year'), DB::raw('MONTH(created_at) AS month'), DB::raw('DAY(created_at) AS day'), DB::raw("COUNT(*) AS count"))
		//			->groupBy("year", "month", "day")
		//			->get();

		return self::selectRaw("COUNT(*) AS count")->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}
}
