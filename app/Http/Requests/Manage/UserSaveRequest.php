<?php

namespace App\Http\Requests\Manage;

use Illuminate\Foundation\Http\FormRequest;
use App\Providers\ValidationServiceProvider ;

class UserSaveRequest extends FormRequest
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
             'id' => 'numeric' ,
             'name_first' => 'required' ,
             'name_last' => 'required' ,
             'email' => 'email|unique:users,email,'.$input['id'].',id',
             'code_melli' => "required|code_melli|unique:users,code_melli,".$input['id'].",id",
             'mobile' => 'required|phone:mobile' ,
        ];
    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[
             'id'  =>  'ed|numeric',
             'action'  =>  'lower',
             'mobile' => 'ed' ,
             'code_melli' => "ed",
        ]);
        return $purified;

    }

}
