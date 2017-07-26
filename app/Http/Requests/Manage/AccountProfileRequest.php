<?php

namespace App\Http\Requests\Manage;

use App\Providers\ValidationServiceProvider;
use Illuminate\Foundation\Http\FormRequest;


class AccountProfileRequest extends FormRequest
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
		$input = $this->all();
		if($input['_submit'] == 'revert') {
			return [];
		}
		else {
			return [
				'name_first'  => "required|min:2",
				'name_last'   => "required|min:2",
				'name_father' => 'required|min:2',
				'code_id'     => "numeric",
				'gender'      => 'required',

				'email'         => 'required|email|unique:users,email,' . user()->id,
				'mobile'        => 'required|phone:mobile',
				'tel_emergency' => 'required|different:mobile|phone:mobile',
				'home_tel'      => 'phone:fixed',
				'work_tel'      => 'phone:fixed',
			];
		}

	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'mobile'        => 'ed',
			'code_id'       => 'ed',
			'gender'        => 'number',
			'tel_emergency' => 'ed',
			'edu_level'     => 'number',
			'edu_city'      => 'number',
			'home_city'     => 'number',
			'home_tel'      => 'ed',
			'work_city'     => 'number',
			'introduction'  => 'number',
			'home_address'  => 'pd',
			'home_postal'   => "ed",
			'work_address'  => 'pd',
			'work_postal'   => "ed",
			'work_tel'      => 'ed',
			'motivation'    => 'pd',
			'alloc_time'    => 'pd',
		]);

		return $purified;

	}

}
