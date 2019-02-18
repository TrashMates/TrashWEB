<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $message_id = $this->message ? $this->message->id : "";

        return [
            "id"         => "required|max:255|unique:messages,id,{$message_id}",
            "user_id"    => "required|exists:users,id",
            "room_id"    => "required|exists:rooms,id",
            "content"    => "required",
            "created_at" => "nullable|date",
            "updated_at" => "nullable|date",
        ];
    }
}
