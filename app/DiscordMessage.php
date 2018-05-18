<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
