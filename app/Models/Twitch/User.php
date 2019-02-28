<?php

namespace App\Models\Twitch;

use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * A user may have many followers
     *
     * @return BelongsToMany
     */
    public function followers(): BelongsToMany
    {
        $event = EventType::where("name", "twitch/followed")->select(["id"])->first()->id;

        return $this->belongsToMany(User::class, "events", "to_user_id", "from_user_id")->where("event_type_id", $event)->orderBy("created_at");
    }

    /**
     * A user may have many followings
     *
     * @return BelongsToMany
     */
    public function followings(): BelongsToMany
    {
        $event = EventType::where("name", "twitch/followed")->select(["id"])->first()->id;

        return $this->belongsToMany(User::class, "events", "from_user_id", "to_user_id")->where("event_type_id", $event)->orderBy("created_at");
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


    /**
     * BUILDER - Fetch all "stalked" users
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeStalking(Builder $query): Builder
    {
        return $query->where("stalking", true);
    }
}
