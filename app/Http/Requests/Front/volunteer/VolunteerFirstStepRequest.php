<?php

namespace App\Http\Requests\site\volunteer;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;
use Illuminate\Support\Facades\Auth;

class VolunteerFirstStepRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check())
            return true;
        else
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
             'name_first' => 'required|persian:60',
             'name_last' => 'required|persian:60',
             'code_melli' => 'required|code_melli',
             'tel_mobile' => 'required|phone:mobile',
             'email' => 'required|email',
             'security' => 'required|captcha:'.$input['key'],
        ];
    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[
            'security'  =>  'ed',
            'code_melli'  =>  'ed',
            'tel_mobile'  =>  'ed',
            'email'  =>  'ed',
            'name_first'  =>  'pd',
            'name_last'  =>  'pd',
        ]);
        return $purified;

    }
}
