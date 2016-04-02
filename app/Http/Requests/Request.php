<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * The attributes that are expected in the request
     *
     * @var array
     */
    protected $expectedFields = [];

    /**
     * Validate the input.
     *
     * @param  \Illuminate\Validation\Factory $factory
     * @return \Illuminate\Validation\Validator
     */
    public function validator($factory)
    {
        return $factory->make(
            $this->sanitizeInput(), $this->container->call([$this, 'rules']), $this->messages()
        );
    }

    /**
     * Sanitize the input.
     *
     * @return array
     */
    protected function sanitizeInput()
    {
        array_filter($this->expectedFields, function ($field) {
            $this->existsOrCreate($field);
            $this->sanitizeField($field);
        }, ARRAY_FILTER_USE_BOTH);

        return $this->all();
    }

    /**
     * Check if a field exists in the input array, if it does not, a default.
     *
     * @param      $field
     * @param null $default
     */
    protected function existsOrCreate($field, $default = null)
    {
        $input = $this->all();
        if (!isset($input[$field])) {
            $input[$field] = $default;
            $this->replace($input);
        }
    }

    /**
     * Call the sanitize method for each field if it exists
     *
     * @param $field
     */
    protected function sanitizeField($field)
    {
        $input = $this->all();
        if (method_exists($this, 'sanitize' . studly_case($field))) {
            $input[$field] = $this->container->call([$this, 'sanitize' . studly_case($field)], [$input[$field]]);
            $this->replace($input);
        }
    }

}
