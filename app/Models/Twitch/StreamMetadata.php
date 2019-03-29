<?php

namespace App\Models\Twitch;

use Illuminate\Database\Eloquent\Model;

class StreamMetadata extends Model
{
    /**
     * All columns that should be treated as dates
     *
     * @var array
     */
    public $dates = [
        "created_at"
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
     * This table doesn't have the default timestamps
     *
     * @var bool
     */
    public $timestamps = false;
}
