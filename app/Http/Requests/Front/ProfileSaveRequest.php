<?php

namespace App\Http\Requests\Front;

use App\Providers\ValidationServiceProvider;
use Carbon\Carbon;
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
            'name_first'  => 'required|persian:60',
            'name_last'   => 'required|persian:60',
            'gender'      => 'required|numeric|min:1|max:3',
            'name_father' => 'required|persian:60',
            'code_id'     => 'numeric',
            'birth_date'  => 'required|date|min:6|before_or_equal:'
                . Carbon::now()->toDateString()
                . '|after_or_equal:'
                . Carbon::now()->subYears(100)->toDateString(),
            'birth_city'  => 'required|numeric|min:1',
            'edu_level'   => 'required|numeric|min:1|max:6',
            'job'         => 'required|persian:60',
            'mobile'      => 'required|phone:mobile',
            'home_tel'    => 'required|phone:fixed',
            'home_city'   => 'required|numeric|min:1',
            'email'       => 'email',
            'password'    => 'same:password2|min:8|max:50|',
            'password2'   => 'required_with:password',
//            'chRegisterAll' => 'required_without_all:chRegisterHeart,chRegisterLung,chRegisterLiver,chRegisterKidney,chRegisterPancreas,chRegisterTissues'

        ];
    }

    public function all()
    {
        $value = parent::all();
        $purified = ValidationServiceProvider::purifier($value, [
            'name_first'    => 'pd',
            'name_last'     => 'pd',
            'name_father'   => 'pd',
            'birth_date'    => 'gDate',
            'marriage_date' => 'gDate',
            'mobile'        => 'ed',
            'password'      => 'ed',
            'password2'     => 'ed',
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
