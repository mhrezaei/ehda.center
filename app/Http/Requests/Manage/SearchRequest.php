<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Role;
use App\Providers\ValidationServiceProvider;


class SearchRequest extends Request
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
			'keyword' => "min:2",
		];

	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'keyword' => "ed" ,
		]);

		return $purified;

	}

}
