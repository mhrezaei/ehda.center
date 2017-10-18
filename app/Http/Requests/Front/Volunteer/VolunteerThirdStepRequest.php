<?php

namespace App\Http\Requests\Front\Volunteer;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class VolunteerThirdStepRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (true and // @TODO: check for exam passed if needed
            (auth()->guest() or !user()->withDisabled()->is_admin()) // If isn't admin
        ) {
            return true;
        } else {
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
        $rules = [
            'name_first'  => 'required|persian:60',
            'name_last'   => 'required|persian:60',
            'gender'      => 'required|numeric|min:1|max:3',
            'name_father' => 'required|persian:60',
            'code_id'     => 'required|numeric',
            // unique where not soft deleted
            'code_melli'  => [
                'code_melli',
                Rule::unique('users')
                    ->whereNull('deleted_at')// Not soft deleted
                    ->whereNotNull('id'), // Not logged in
            ],
            'birth_date'  => 'required|date|min:6|before_or_equal:'
                . Carbon::now()->toDateString()
                . '|after_or_equal:'
                . Carbon::now()->subYears(100)->toDateString(),
            'birth_city'  => 'required|numeric|min:1',
            'marital'     => 'required|numeric|min:1|max:2',
            'email'       => 'email',

            'edu_level' => 'required|numeric|min:1|max:6',
            'edu_field' => 'required|persian:60',
            'edu_city'  => 'required|numeric|min:1',

            'mobile'        => 'required|phone:mobile',
            'tel_emergency' => 'required|phone:fixed',

            'home_city'        => 'required|numeric|min:1',
            'home_address'     => 'required|persian:60',
            'home_tel'         => 'required|phone:fixed|min:10',
            'home_postal_code' => 'digits:10',

            'job'              => 'required|persian:60',
            'work_city'        => 'numeric',
            'work_address'     => 'persian:60',
            'work_tel'         => 'phone:fixed',
            'work_postal_code' => 'digits:10',

            'familiarization' => 'required|numeric|min:1|max:4',
            'motivation'      => 'required|persian:60',
            'alloc_time'      => 'required',

            'activity' => 'array',
        ];

        if(auth()->guest()) {
            $rules['password'] = 'required|same:password2|min:8|max:50';
        }

        return $rules;
    }

    public function all()
    {
        $value = parent::all();

        if (auth()->guest()) {
            if (isset($value['id'])) {
                unset($value['id']);
            }
        } else {
            unset($value['code_melli']);
            $value['id'] = user()->id;
        }

        $purified = ValidationServiceProvider::purifier($value, [

            'name_first'  => 'pd',
            'name_last'   => 'pd',
            'gender'      => 'ed',
            'name_father' => 'pd',
            'code_id'     => 'ed',
            'birth_date'  => 'gDate',
            'birth_city'  => 'ed',
            'marital'     => 'ed',
            'email'       => 'ed',

            'edu_level' => 'ed',
            'edu_field' => 'pd',
            'edu_city'  => 'ed',

            'tel_mobile'    => 'ed',
            'tel_emergency' => 'ed',

            'home_city'        => 'ed',
            'home_address'     => 'pd',
            'home_tel'         => 'ed',
            'home_postal_code' => 'ed',

            'job'              => 'pd',
            'work_city'        => 'ed',
            'work_address'     => 'pd',
            'work_tel'         => 'ed',
            'work_postal_code' => 'ed',

            'familization' => 'ed',
            'motivation'   => 'pd',
            'alloc_time'   => 'pd',
        ]);
        return $purified;

    }
}
