<?php

namespace App\Http\Requests;

use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class DrawingCodeRequest extends FormRequest
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
            'code1' => 'required|numeric' ,
            'code2' => 'required|numeric' ,
            'code3' => 'required|numeric',
            'code4' => 'required|numeric' ,
        ];
    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[
            'code1'  =>  'ed',
            'code2'  =>  'ed',
            'code3' => 'ed' ,
            'code4' => 'ed' ,
        ]);
        return $purified;

    }
}
