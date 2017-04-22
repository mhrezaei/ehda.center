<?php

namespace App\Http\Requests\Front;

use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class NewPasswordRequest extends FormRequest
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
        return [
            'new_password' => 'same:new_password2|min:8|max:50|',
//            'new_password2' => 'required_with:password|same:password|min:8|max:50|',
        ];
    }
//
//    public function all()
//    {
//        $value = parent::all();
//
//        $purified = ValidationServiceProvider::purifier($value, [
//            'code_melli' => 'ed',
//            'mobile' => 'ed',
//            'email' => 'ed',
//        ]);
//        return $purified;
//    }
}
