<?php

namespace App\Models\Twitch;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Community extends Model
{
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
     * A community is used by many streams
     * [PIVOT TABLE]
     *
     * @return BelongsToMany
     */
    public function streams(): BelongsToMany
    {
        return $this->belongsToMany(Stream::class);
    }
}
