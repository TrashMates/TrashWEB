<?php

namespace App\Jobs;

use App\Models\Twitch\Game;
use App\Models\Twitch\Stream;
use App\Models\Twitch\StreamMetadata;
use App\Models\Twitch\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use TiCubius\TwitchAPI\Facades\Games;
use TiCubius\TwitchAPI\Facades\Users;

class FetchStreamsForGame implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $game;

    /**
     * Create a new job instance.
     *
     * @param Game $game
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $streams = Games::fetchStreams($this->game->id);
        $users_id = $streams->pluck("user_id")->toArray();
        $users = Users::fetchFromId($users_id);

        foreach ($users as $user) {
            User::updateOrCreate([
                "id" => $user->id,
            ], [
                "broadcaster_type"  => $user->broadcaster_type ?? null,
                "description"       => $user->description ?? null,
                "offline_image_url" => $user->offline_image_url ?? null,
                "profile_image_url" => $user->profile_image_url ?? null,
                "type"              => $user->type ?? null,
                "username"          => $user->display_name ?? $user->login,
            ]);
        }

        foreach ($streams as $stream) {
            Stream::updateOrCreate([
                "id"      => $stream->id,
                "game_id" => $stream->game_id,
                "user_id" => $stream->user_id,
            ], [
                "language"   => $stream->language,
                "title"      => $stream->title,
                "type"       => $stream->type,
                "created_at" => Carbon::parse($stream->started_at)->format("Y-m-d H:i:s"),
            ]);

            StreamMetadata::create([
                "stream_id" => $stream->id,
                "number"    => 0,
                "viewers"   => $stream->viewer_count,
            ]);
        }

        $expiredStreams = Stream::where("game_id", $this->game->id)->whereNull("stopped_at")->whereNotIn("id", $streams->pluck("id"))->get();
        $expiredStreams->each(function (Stream $stream) {
            $stream->update([
                "stopped_at" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        });
    }
}
