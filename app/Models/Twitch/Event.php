<?php

namespace App\Models\Twitch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    /**
     * All columns can be modified
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * An event is made by one user
     *
     * @return BelongsTo
     */
    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class, "from_user_id");
    }

    /**
     * An event may concern another user
     *
     * @return BelongsTo
     */
    public function to(): BelongsTo
    {
        return $this->belongsTo(User::class, "to_user_id");
    }

    /**
     * An event is a certain type
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(EventType::class, "event_type_id");
    }
}
