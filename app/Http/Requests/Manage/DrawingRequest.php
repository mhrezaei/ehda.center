<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Package;
use App\Providers\ValidationServiceProvider;


class DrawingRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true; //@TODO?
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'random_number' => "required|numeric|min:1|max:".session()->get('line_number'),
		];

	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'random_number' => "ed",
		]);

		return $purified;

	}

}
