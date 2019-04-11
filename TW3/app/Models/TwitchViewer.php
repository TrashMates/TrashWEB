<?php

namespace App\Models;

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

	public static function getStats()
	{
		return self::selectRaw("COUNT(*) AS count")
			->selectRaw("DATE(created_at) AS date")
			->groupBy("date")
			->orderBy("date", "ASC");
	}

	public function messages()
	{
		return $this->hasMany(TwitchMessage::class, 'viewer_id');
	}

	public function events()
	{
		return $this->hasMany(TwitchEvent::class, 'viewer_id');
	}
}
