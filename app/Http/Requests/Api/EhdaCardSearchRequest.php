<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;

class EhdaCardSearchRequest extends Request
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
            'token'  =>  'ed|decrypt',
            'code_melli'  =>  'ed',
        ]);
        return $purified;

    }
}
