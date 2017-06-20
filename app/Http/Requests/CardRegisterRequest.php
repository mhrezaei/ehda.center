<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;
use Illuminate\Support\Facades\Session;

class CardRegisterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->step and $this->step >= 1 and $this->step <= 2) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->step) {
            case 1;
                return [
                    'name_first' => 'required|persian:60',
                    'name_last'  => 'required|persian:60',
                    'code_melli' => 'required|code_melli',
//             'security' => 'required|captcha:'.$input['key'], @TODO: new human validation
                ];
                break;
            case 2;
                return [
                    'name_first'  => 'required|persian:60',
                    'name_last'   => 'required|persian:60',
                    'gender'      => 'required|numeric|min:1|max:3',
                    'name_father' => 'required|persian:60',
                    'code_id'     => 'required|numeric',
                    'birth_date'  => 'required|min:6',
                    'birth_city'  => 'required|numeric|min:1',
                    'edu_level'   => 'required|numeric|min:1|max:6',
                    'job'         => 'required|persian:60',
                    'tel_mobile'  => 'required|phone:mobile',
                    'home_tel'    => 'required|phone:fixed',
                    'home_city'   => 'required|numeric|min:1',
                    'email'       => 'email',
                    'password'    => 'required|same:password2|min:8|max:50|',
//            'chRegisterAll' => 'required_without_all:chRegisterHeart,chRegisterLung,chRegisterLiver,chRegisterKidney,chRegisterPancreas,chRegisterTissues'

                ];
                break;
        }
    }

    public function all()
    {
        $value = parent::all();
        switch ($value['step']) {
            case 1:
                $purified = ValidationServiceProvider::purifier($value,[
                    'security'  =>  'ed',
                    'code_melli'  =>  'ed',
                    'name_first'  =>  'pd',
                    'name_last'  =>  'pd',
                ]);
                break;
            case 2:
                $purified = ValidationServiceProvider::purifier($value, [
                    'name_first'  => 'pd',
                    'name_last'   => 'pd',
                    'gender'      => 'ed',
                    'name_father' => 'pd',
                    'code_id'     => 'ed',
                    'birth_date'  => 'ed',
                    'birth_city'  => 'ed',
                    'edu_level'   => 'ed',
                    'job'         => 'pd',
                    'tel_mobile'  => 'ed',
                    'home_tel'    => 'ed',
                    'home_city'   => 'ed',
                    'email'       => 'ed',
                    'password'    => 'ed',
                    'password2'   => 'ed',
                ]);
                break;
        }
        return $purified;

    }
}
