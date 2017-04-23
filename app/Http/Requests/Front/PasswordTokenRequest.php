<?php

namespace App\Http\Requests\Front;

use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class PasswordTokenRequest extends FormRequest
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
            'code_melli' => 'required_if:direct_request,true|code_melli|exists:users,code_melli',
            'password_reset_token' => 'required|numeric',
        ];
    }

    public function all()
    {
        $value = parent::all();

        if (session()->get('resetingPasswordNationalId')) {
            session()->keep(['resetingPasswordNationalId']);
            $this->request->add(['code_melli' => session()->get('resetingPasswordNationalId')]);
        } else {
            $this->request->add(['direct_request' => true]);
        }


        $purified = ValidationServiceProvider::purifier($value, [
            'code_melli' => 'ed',
            'password_reset_token' => 'ed',
        ]);

        return $purified;

    }
}
