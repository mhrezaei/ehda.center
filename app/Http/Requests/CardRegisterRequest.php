<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CardRegisterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->_step and $this->_step >= 1 and $this->_step <= 3) {
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
        switch ($this->_step) {
            case 1: // Step 1
                return [
                    'name_first' => 'required|persian:60',
                    'name_last'  => 'required|persian:60',
                    'code_melli'  => 'required|code_melli',
//             'security' => 'required|captcha:'.$input['key'], @TODO: new human validation
                ];
                break;
            case 2: // Step 2
                return [
                    'code_melli'  => 'required|code_melli',
                    'name_first'  => 'required|persian:60',
                    'name_last'   => 'required|persian:60',
                    'gender'      => 'required|numeric|min:1|max:3',
                    'name_father' => 'required|persian:60',
//                    'code_id'     => 'required|numeric',
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
                    'password'    => 'required|same:password2|min:8|max:50|',
//            'chRegisterAll' => 'required_without_all:chRegisterHeart,chRegisterLung,chRegisterLiver,chRegisterKidney,chRegisterPancreas,chRegisterTissues'

                ];
                break;
            case 3:
                return [
                    'code_melli'  => 'required|code_melli',
                ];
                break;
        }
    }

    public function all()
    {
        $value = parent::all();
        switch ($value['_step']) {
            case 1:
                $purified = ValidationServiceProvider::purifier($value, [
                    'security'   => 'ed',
                    'code_melli' => 'ed',
                    'name_first' => 'pd',
                    'name_last'  => 'pd',
                ]);
                break;
            case 2:
                $purified = ValidationServiceProvider::purifier($value, [
                    'name_first'  => 'pd',
                    'name_last'   => 'pd',
                    'gender'      => 'ed',
                    'name_father' => 'pd',
                    'code_id'     => 'ed',
                    'birth_date'  => 'gDate',
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
            case 3:
                $purified = ValidationServiceProvider::purifier($value, [
                    'db-check' => 'decrypt',
                ]);
                break;
        }
        return $purified;

    }
}
