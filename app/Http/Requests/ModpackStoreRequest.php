<?php

namespace App\Http\Requests;

class ModpackStoreRequest extends ApiRequest
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
            'name' => 'required',
            'published_at' => 'date_format:"c"',
        ];
    }
}
