<?php

namespace App\Http\Requests\Front;

use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ProfileSaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return user()->exists;
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
            'name_first' => 'required|persian:60',
            'name_last' => 'required|persian:60',
            'name_father' => 'persian:60',
            'birth_date' => 'required|before_or_equal:' . date('Y-m-d'),
            'education' => 'numeric|in:' . implode(',', array_keys(\Illuminate\Support\Facades\Lang::get('people.edu_level_full'))),
            'mobile' => 'required|phone:mobile',
            'home_tel' => 'required|phone:fixed',
            'email' => 'email',
            'postal_code' => 'postal_code',
            'address' => 'persian:60',
            'gender' => 'required|numeric|in:' . implode(',', array_keys(\Illuminate\Support\Facades\Lang::get('forms.gender'))),
            'marital' => 'required|numeric|in:1,2',
            'marriage_date' => 'required_if:marital,2',
            'new_password' => 'same:new_password2|min:8|max:50|',
//            'new_password2' => 'required_with:password|same:password|min:8|max:50|',
        ];
    }

    public function all()
    {
        $value = parent::all();
        $purified = ValidationServiceProvider::purifier($value, [
            'name_first' => 'pd',
            'name_last' => 'pd',
            'name_father' => 'pd',
            'birth_date' => 'gDate',
            'marriage_date' => 'gDate',
            'mobile' => 'ed',
            'password' => 'ed',
            'password2' => 'ed',
        ]);
        return $purified;

    }

//    public function messages()
//    {
//        \Illuminate\Validation\Validator::replacer('before_or_equal', function ($message, $attribute, $rule, $parameters) {
//            if (isset($parameters[1]) and $parameters[1] == 'jDate') {
//                $parameters[1] = '';
//
//                $gUnixtime = strtotime($parameters[0]);
//                $jDate = jDateTime::toJalali(date('Y', $gUnixtime), date('m', $gUnixtime), date('d', $gUnixtime));
//                $jDateStr = implode('/', $jDate);
//                return str_replace(':date', $jDateStr, $message);
//            }
//        });
//        dd($this->validationData());
//        $messages = parent::messages();
//        $messages['before_or_equal'] = '';
//        return $messages;
//    }

}
