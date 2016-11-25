<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BuildStoreRequest extends ApiRequest
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
            'published' => 'boolean',
            'tags' => 'array',
            'version' => [
                'required',
                Rule::unique('builds'), // TODO: Need to check where('modpack_id', $modpack->id)
            ],
        ];
    }
}
