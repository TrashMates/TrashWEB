<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Foundation\Http\FormRequest;

class EventTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $eventType_id = $this->eventType ? $this->eventType->id : "";

        return [
            "id"         => "nullable|unique,id,{$eventType_id}",
            "name"       => "required|unique:event_types,name,{$eventType_id}",
            "created_at" => "nullable|date",
            "updated_at" => "nullable|date",
        ];
    }
}
