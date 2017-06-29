<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;

class GetTokenRequest extends Request
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

        ];
    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[
            'username'  =>  'ed',
            'password'  =>  'ed',
        ]);
        return $purified;

    }
}
