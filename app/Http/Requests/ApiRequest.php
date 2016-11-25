<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApiRequest extends FormRequest
{
    /**
     * Customize the error messages to be in-line with JSON API taxonomy
     * (mostly, this is replacing 'field' with 'attribute').
     *
     * @return array
     */
    public function messages()
    {
        return [
            'attributes.*.required' => 'the :attribute attribute is required',
        ];
    }

    /**
     * Get attributes from resource object for validation.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->input('data.attributes');
    }
}
