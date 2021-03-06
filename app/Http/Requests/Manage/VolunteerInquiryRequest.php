<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Category;
use App\Providers\ValidationServiceProvider;


class VolunteerInquiryRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return user()->userRolesArray('create' , [] , model('role')::adminRoles()) ;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'code_melli' => "required|code_melli"
		];

	}

	public function all()
	{
		$value	= parent::all();
		$purified = ValidationServiceProvider::purifier($value,[
			'code_melli'  =>  'ed',
		]);
		return $purified;

	}

}
