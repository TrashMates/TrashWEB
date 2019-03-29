<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Foundation\Http\FormRequest;

class StreamRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $strame_id = $this->stream ? $this->stream->id : "";

        return [
            "id"               => "required|max:255|unique:streams,id,${strame_id}",
            "game_id"          => "required|exists:games,id",
            "user_id"          => "required|exists:users,id",
            "communities.*.id" => "nullable|max:255|exists:communities,id",
            "language"         => "required|max:255",
            "title"            => "required|max:255",
            "type"             => "required|max:255",
            "created_at"       => "required|date",
            "stopped_at"       => "nullable|date",
        ];
    }
}
