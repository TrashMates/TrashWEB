<?php

namespace App\Models\Twitch;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Model
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
     * A user may have many events
     *
     * @return HasMany
     */
    public function eventsAuthor(): HasMany
    {
        return $this->hasMany(Event::class, "from_user_id")->orderBy("created_at");
    }

    /**
     * A user may have many events
     *
     * @return HasMany
     */
    public function eventsReceiver(): HasMany
    {
        return $this->hasMany(Event::class, "to_user_id")->orderBy("created_at");
    }

    public function followers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Event::class, "to_user_id", "id", "id", "from_user_id");
    }

    /**
     * A user may have many messages
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * A user may have many rooms
     *
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * A user may have many streams
     *
     * @return HasMany
     */
    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class);
    }
}
