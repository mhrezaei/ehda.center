<?php

namespace App\Http\Requests\Front\Volunteer;

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
        // If the user is logged in should be start registering as volunteer from next step
        if (auth()->guest())
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
            'name_last'  => 'required|persian:60',
            'code_melli' => 'required|code_melli',
            'mobile'     => 'required|phone:mobile',
            'email'      => 'required|email',
        ];
    }

    public function all()
    {
        $value = parent::all();
        $purified = ValidationServiceProvider::purifier($value, [
            'code_melli' => 'ed',
            'mobile'     => 'ed',
            'email'      => 'ed',
            'name_first' => 'pd',
            'name_last'  => 'pd',
        ]);
        return $purified;

    }
}

//mhr
