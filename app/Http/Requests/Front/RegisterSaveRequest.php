<?php

namespace App\Http\Requests\Front;

use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class RegisterSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !user()->exists;
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
            'name_first' => 'required|persian:60' ,
            'name_last' => 'required|persian:60' ,
            'code_melli' => 'required|code_melli',
            'mobile' => 'required|phone:mobile' ,
            'password' => 'required|same:password2|min:8|max:50|',
        ];
    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[
            'name_first'  =>  'pd',
            'name_last'  =>  'pd',
            'code_melli' => 'ed' ,
            'mobile' => 'ed' ,
            'password' => 'ed',
            'password2' => 'ed',
        ]);
        return $purified;

    }
}
