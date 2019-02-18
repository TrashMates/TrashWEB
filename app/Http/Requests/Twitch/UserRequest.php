<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * When the validation fails, return the errors
     *
     * @param Validator $validator
     * @return HttpResponseException
     */
    protected function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(response()->json(["errors" => $validator->errors()], 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = $this->user ? $this->user->id : "";

        return [
            "id"                => "required|max:255|unique:users,id,{$user_id}",
            "broadcaster_type"  => "nullable|max:255",
            "description"       => "nullable|max:255",
            "offline_image_url" => "nullable|max:255",
            "profile_image_url" => "nullable|max:255",
            "type"              => "nullable|max:255",
            "username"          => "required|max:255",
            "created_at"        => "nullable|date",
            "updated_at"        => "nullable|date",

        ];
    }
}
