<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscordEvent extends Model
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
		return $this->BelongsTo(DiscordViewer::class, "userid");
	}
}
