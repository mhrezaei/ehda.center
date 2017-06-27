<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;

class EhdaCardRegisterRequest extends Request
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
            'code_melli'  =>  'ed',
            'gender' => 'ed',

            'name_first'  =>  'pd',
            'name_last'  =>  'pd',
            'name_father' => 'pd',

            'code_id' => 'ed',

            'birth_date' => 'ed',
            'birth_city' => 'ed',

            'edu_level' => 'ed',


            'tel_mobile' => 'ed',

            'home_city' => 'ed',

            'token' => 'ed|decrypt',

        ]);
        return $purified;
    }
}
