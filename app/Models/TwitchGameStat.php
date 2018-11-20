<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TwitchGameStat extends Model
{
	protected $fillable = ["game_id"];

	/**
	 * @return BelongsTo
	 */
	public function game(): BelongsTo
	{
		return $this->belongsTo(TwitchGame::class);
	}

	/**
	 * @return HasMany
	 */
	public function streams(): HasMany
	{
		return $this->hasMany(TwitchGameStream::class, "stat_id")->orderBy("viewers", "DESC");
	}
}
