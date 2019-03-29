<?php

namespace App\Http\Requests\Twitch;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GameRequest extends FormRequest
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
        $game_id = $this->game ? $this->game->id : "";

        return [
            "id"          => "sometimes|max:255|unique:games,id,{$game_id}",
            "box_art_url" => "nullable|max:255",
            "name"        => "sometimes|max:255",
            "stalking"    => "nullable|boolean",
            "created_at"  => "nullable|date",
            "updated_at"  => "nullable|date",
        ];
    }
}
