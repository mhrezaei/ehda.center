<?php

namespace App\Http\Requests\Front;

use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'code_melli' => 'required|code_melli|exists:users,code_melli',
            'type' => 'required|in:email,mobile',
            'email' => 'required_if:type,email|email',
            'mobile' => 'required_if:type,mobile|phone:mobile',
        ];
    }

    public function all()
    {
        $value = parent::all();

        $purified = ValidationServiceProvider::purifier($value, [
            'code_melli' => 'ed',
            'mobile' => 'ed',
            'email' => 'ed',
        ]);
        return $purified;

    }
}
