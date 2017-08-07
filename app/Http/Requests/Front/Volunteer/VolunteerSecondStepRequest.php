<?php

namespace App\Http\Requests\site\volunteer;

use App\Http\Requests\Request;
use App\Models\User;
use App\Providers\ValidationServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VolunteerSecondStepRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::check())
        {
            $user = Auth::user();
            if ($user->isActive('volunteer') and $user->exam_passed_at)
            {
                return false;
            }
            elseif ($user->volunteer_status == 3 and $user->exam_passed_at)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        elseif (Session::get('volunteer_first_step'))
        {
            $data = Session::get('volunteer_first_step');
            $user = User::selectBySlug($data['code_melli'], 'code_melli');
            if ($user)
            {
                if ($user->volunteer_status == 2 or $user->volunteer_status > 3 or $user->volunteer_status < 0)
                {
                    return false;
                }
                elseif ($user->volunteer_status == 1)
                {
                    if (Carbon::parse($user->exam_passed_at)->diffInHours(Carbon::now()) < 24)
                        return false;
                    else
                        return true;
                }
                elseif ($user->volunteer_status == 3)
                {
                    if ($user->exam_passed_at)
                        return false;
                    else
                        return true;
                }
                else
                {
                    return true;
                }
            }
            else
            {
                return true;
            }
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

        ];
    }

    public function all()
    {
        $value	= parent::all();
        $purified = ValidationServiceProvider::purifier($value,[

        ]);
        return $purified;

    }
}
