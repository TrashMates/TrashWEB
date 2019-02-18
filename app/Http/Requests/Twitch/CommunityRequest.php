<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Foundation\Http\FormRequest;

class CommunityRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $community_id = $this->community ? $this->community->id : "";

        return [
            "id"         => "required|max:255|unique:communities,id,{$community_id}",
            "name"       => "nullable|max:255",
            "created_at" => "nullable|date",
            "updated_at" => "nullable|date",
        ];
    }
}
