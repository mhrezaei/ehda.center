<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Domain;
use App\Providers\ValidationServiceProvider;


class DomainSaveRequest extends Request
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
		$id    = $input['id'];
		if($input['_submit'] == 'save') {
			return [
				'title' => "required|unique:domains,title,$id",
				'alias' => "alpha_dash|not_in:" . Domain::$reserved_slugs . "|unique:domains,alias,$id",
				'slug'  => "required|alpha_dash|not_in:" . Domain::$reserved_slugs . "|unique:domains,slug,$id",
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
			'slug'  => 'lower',
			'alias' => 'lower',
		]);

		return $purified;

	}

}
