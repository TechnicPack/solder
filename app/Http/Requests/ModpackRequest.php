<?php

namespace App\Http\Requests;

use Auth;

class ModpackRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
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
            $id = ',' . $this->modpack->id;
        }

        return [
            'name' => 'required|unique:modpacks,name'.$id,
            'slug' => 'required|unique:modpacks,slug'.$id,
        ];
    }
}
