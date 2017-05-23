<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Category;
use App\Providers\ValidationServiceProvider;


class GoodSaveRequest extends Request
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
		if($input['_submit'] == 'save') {
			return [
				'price'           => "required|numeric",
				'discount_amount' => "numeric|min:0,max:" . $input['price'],
			];
		}
		else {
			return [];
		}

	}

	public function all()
	{
		$value    = parent::all();
		$purified = ValidationServiceProvider::purifier($value, [
			'price'           => "ed|numeric",
			'discount_amount' => "ed|numeric",
		]);

		return $purified;

	}

}
