<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TwitchGameStream extends Model
{
	public $timestamps = false;

	protected $fillable = [
		"id",
		"stat_id",
		"game_id",
		"channel_id",
		"title",
		"language",
		"viewers",
		"created_at"
	];

	/**
	 * @return BelongsTo
	 */
	public function game(): BelongsTo
	{
		return $this->belongsTo(TwitchGame::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function stat(): BelongsTo
	{
		return $this->belongsTo(TwitchGameStat::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function channel(): BelongsTo
	{
		return $this->belongsTo(TwitchChannel::class);
	}
}
