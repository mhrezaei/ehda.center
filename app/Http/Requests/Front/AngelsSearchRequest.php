<?php

namespace App\Http\Requests\Front;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;

class AngelsSearchRequest extends Request
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $input = $this->all();
        return [
            'angel_name' => 'required|persian:60',
        ];
    }

    public function all()
    {
        $value = parent::all();
        $purified = ValidationServiceProvider::purifier($value, [
            'angel_name' => 'pd',
        ]);
        return $purified;

    }
}
