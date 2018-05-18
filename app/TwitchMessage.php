<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
