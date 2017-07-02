<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Category;
use App\Providers\ValidationServiceProvider;


class MessageSendRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		$input = $this->all() ;
		$role = $input['role'] ;
		return user()->as('admin')->can("users-$role.send") ;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'message'=> 'required',
			'title' => 'required' ,
		];

	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'role' => "decrypt" ,
		]);

		return $purified;

	}

}
