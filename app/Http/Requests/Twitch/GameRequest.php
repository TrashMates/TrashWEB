<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Foundation\Http\FormRequest;

class GameRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $game_id = $this->game ? $this->game->id : "";

        return [
            "id"          => "required|max:255|unique:games,id,{$game_id}",
            "box_art_url" => "nullable|max:255",
            "name"        => "required|max:255",
            "created_at"  => "nullable|date",
            "updated_at"  => "nullable|date",
        ];
    }
}
