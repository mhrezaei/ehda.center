<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Role;
use App\Providers\ValidationServiceProvider;


class VolunteerModerateChangesRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true; // <~~ Will be checked in the controller
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$input = $this->all();
		if($input['_submit'] == 'reject') {
			return [
				'reject_reason' => 'required',
			];
		}
		else {
			return [
				'email'         => 'email|unique:users,email,' . $input['id'],
				'tel_mobile'    => 'phone:mobile',
				'tel_emergency' => 'different:tel_mobile|phone:mobile',
				'home_tel'      => 'phone:fixed',
				'work_tel'      => 'phone:fixed',
			];
		}

	}

	public function all()
	{
		$value	= parent::all();
		$purified = ValidationServiceProvider::purifier($value,[
			'tel_mobile' => 'ed' ,
			'tel_emergency'=> 'ed' ,
			'edu_level' => 'number',
			'edu_city' => 'number',
			'home_city' => 'number',
			'home_tel' => 'ed' ,
			'work_city' => 'number',
			'introduction' => 'number' ,
			'home_address' => 'pd' ,
			'work_address' => 'pd' ,
			'work_tel' => 'ed' ,
			'motivation' => 'pd' ,
			'alloc_time' => 'pd' ,
		]);
		return $purified;

	}

}
