<?php

namespace App\Models\Twitch;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stream extends Model
{
    use Filterable;

    /**
     * All columns that should be treated as dates
     *
     * @var array
     */
    public $dates = [
        "stopped_at"
    ];

    /**
     * The primary key is not an autoincrement
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * All columns can be modified
     *
     * @var array
     */
    protected $guarded = [];


    /**
     * A stream may have many communities
     * [PIVOT TABLE]
     *
     * @return BelongsToMany
     */
    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(Community::class);
    }

    /**
     * A stream is made on one game
     *
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * A stream may have many metadata
     *
     * @return HasMany
     */
    public function metadata(): HasMany
    {
        return $this->hasMany(StreamMetadata::class);
    }

    /**
     * A stream is made by one user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
