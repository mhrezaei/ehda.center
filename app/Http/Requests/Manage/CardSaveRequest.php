<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Providers\ValidationServiceProvider;
use Illuminate\Support\Facades\Auth;


class CardSaveRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$input = $this->all();
		if($input['id'] == 0) {
			return user()->as('admin')->can('users-card-holder.create');
		}
		else {
			return user()->as('admin')->can('users-card-holder.edit');
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
		$id    = $input['id'];

		return [
			'name_first'  => "required|min:2",
			'name_last'   => "required|min:2",
			'name_father' => 'min:2',
			'code_melli'  => "required|code_melli|unique:users,code_melli," . $id, //@TODO: Consider removing the unique check!
			//'code_id'     => "required",
			'email'       => 'email|',
			'gender'      => 'required',
			'birth_city'  => '',
			'edu_level'   => 'numeric',
			'home_city'   => 'required',
			'birth_date'  => 'required|date',
			'mobile'  => 'required|phone:mobile',
			'home_tel'    => 'phone:fixed',
			'work_tel'    => 'phone:fixed',
		];

	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'id' => "decrypt" ,
			'code_melli'    => 'ed',
			'code_id'       => 'ed',
			'gender'        => 'number',
			'mobile'        => 'ed',
			'tel_emergency' => 'ed',
			'edu_city'      => 'number',
			'home_tel'      => 'ed',
			'home_address'  => 'pd',
			'home_postal'   => "ed",
		]);

		return $purified;

	}

}
