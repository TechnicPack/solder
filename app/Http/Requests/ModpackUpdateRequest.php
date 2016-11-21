<?php

namespace App\Http\Requests;

class ModpackUpdateRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request attributes.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'filled',
            'published' => 'boolean',
        ];
    }
}
