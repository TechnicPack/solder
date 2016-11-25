<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ReleaseStoreRequest extends ApiRequest
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
            'version' => [
                'required',
                Rule::unique('releases'), // TODO: Need to check where('mod_id', $mod->id)
            ],
        ];
    }
}
