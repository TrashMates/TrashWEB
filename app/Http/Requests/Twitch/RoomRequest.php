<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $room_id = $this->room ? $this->room->id : "";

        return [
            "id"         => "required|max:255|unique:rooms,id,${room_id}",
            "user_id"    => "required|exists:users,id",
            "name"       => "required|max:255",
            "topic"      => "required|max:255",
            "created_at" => "nullable|date",
            "updated_at" => "nullable|date",
        ];
    }
}
