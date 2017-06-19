<?php

namespace App\Http\Requests\site\volunteer;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;
use Illuminate\Support\Facades\Session;

class VolunteerThirdStepRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Session::get('volunteer_exam_passed'))
        {
            return true;
        }
        else
        {
            return false;
        }
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
            'gender' => 'required|numeric|min:1|max:3',
            'name_father' => 'required|persian:60',
            'code_id' => 'required|numeric',
            'birth_date' => 'required|min:6',
            'birth_city' => 'required|numeric|min:1',
            'marital' => 'required|numeric|min:1|max:2',
            'email' => 'email',

            'edu_level' => 'required|numeric|min:1|max:6',
            'edu_field' => 'required|persian:60',
            'edu_city' => 'required|numeric|min:1',

            'tel_mobile' => 'required|phone:mobile',
            'tel_emergency' => 'required|phone:fixed',

            'home_city' => 'required|numeric|min:1',
            'home_address' => 'required|persian:60',
            'home_tel' => 'required|phone:fixed',
            'home_postal_code' => 'digits:10',

            'job' => 'required|persian:60',
            'work_city' => 'numeric',
            'work_address' => 'persian:60',
            'work_tel' => 'phone:fixed',
            'work_postal_code' => 'digits:10',

            'familization' => 'required|numeric|min:1|max:4',
            'motivation' => 'required|persian:60',
            'alloc_time' => 'required',

            'activity' => 'array',
        ];
    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[

            'name_first'  =>  'pd',
            'name_last'  =>  'pd',
            'gender' => 'ed',
            'name_father' => 'pd',
            'code_id' => 'ed',
            'birth_date' => 'ed',
            'birth_city' => 'ed',
            'marital' => 'ed',
            'email' => 'ed',

            'edu_level' => 'ed',
            'edu_field' => 'pd',
            'edu_city' => 'ed',

            'tel_mobile' => 'ed',
            'tel_emergency' => 'ed',

            'home_city' => 'ed',
            'home_address' => 'pd',
            'home_tel' => 'ed',
            'home_postal_code' => 'ed',

            'job' => 'pd',
            'work_city' => 'ed',
            'work_address' => 'pd',
            'work_tel' => 'ed',
            'work_postal_code' => 'ed',

            'familization' => 'ed',
            'motivation' => 'pd',
            'alloc_time' => 'pd',
        ]);
        return $purified;

    }
}
