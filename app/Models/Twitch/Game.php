<?php

namespace App\Models\Twitch;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    use Filterable;

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
     * A game is played on many streams
     *
     * @return HasMany
     */
    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class);
    }
}
