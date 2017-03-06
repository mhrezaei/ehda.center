<?php

namespace App\Http\Requests\Manage;

use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class ChangeSelfPasswordRequest extends FormRequest
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
			'current_password' => "required",
			'new_password'=> 'required|min:8|max:60',
			'password2' => "required|same:new_password",
		];
	}

	public function all()
	{
		$value	= parent::all();
		$purified = ValidationServiceProvider::purifier($value,[
				'password' => 'ed' ,
		]);
		return $purified;

	}

}
