<?php

namespace App\Http\Requests;

use Auth;

class BuildRequest extends Request
{
    /**
     * The attributes that are expected in the request
     *
     * @var array
     */
    protected $expectedFields = ['version', 'minecraft', 'min_memory', 'published', 'private'];

    /**
     * Sanitize function for the phone field.
     *
     * @param $input
     * @return string
     */
    public function sanitizePhone($input)
    {
        return phoneToDigits($input);
    }

    /**
     * Sanitize function for the postal-code field.
     *
     * @param $input
     * @return string
     */
    public function sanitizePostalCode($input)
    {
        return postalToAlphaNum($input);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    public function sanitizePublished($input)
    {
        return $this->has('published');
    }

    public function sanitizePrivate($input)
    {
        return $this->has('private');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = null;
        if ($this->method() == 'PATCH') {
            $id = ',' . $this->build->id;
        }

        return [
            'version'    => 'required|unique:builds,version' . $id,
            'minecraft'  => 'required',
            'min_memory' => 'numeric',
        ];
    }
}
