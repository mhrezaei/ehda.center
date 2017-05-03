<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Posttype;
use App\Providers\ValidationServiceProvider;
use Illuminate\Support\Facades\Auth;


class PosttypeDownstreamSaveRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
		//Permission is to be checked in the controller
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$array = [];
		foreach(Posttype::$downstream as $item) {
			$array[ $item['name'] ] = $item['rules'];
		}

		return $array;

	}

	public function all()
	{
		$value = parent::all();
		$array = [];
		foreach(Posttype::$downstream as $item) {
			if(isset($item['purifier'])) {
				$array[ $item['name'] ] = $item['purifier'];
			}
		}
		$purified = ValidationServiceProvider::purifier($value, $array);

		return $purified;

	}

}
