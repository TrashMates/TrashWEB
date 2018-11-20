<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TwitchGame extends Model
{
	public $timestamps = false;

	protected $fillable = ["id", "name", "picture"];

	/**
	 * @return HasMany
	 */
	public function stats(): HasMany
	{
		return $this->hasMany(TwitchGameStat::class, "game_id");
	}
}
