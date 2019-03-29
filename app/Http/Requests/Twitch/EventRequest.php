<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "from_id"       => "required|exists:users,id",
            "to_id"         => "nullable|exists:users,id",
            "event_type_id" => "required|exists:event_types,id",
            "content"       => "nullable",
            "created_at"    => "nullable|date",
            "updated_at"    => "nullable|date",
        ];
    }
}
