<?php

namespace App\Models\Twitch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventType extends Model
{
    /**
     * All columns can be modified
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * An event type is used by many events
     *
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
