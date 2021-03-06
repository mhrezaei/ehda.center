<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Folder;
use App\Providers\ValidationServiceProvider;


class FolderSaveRequest extends Request
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
				'title' => 'required|',
				'slug'  => 'required|alpha_dash|not_in:' . Folder::$reserved_slugs ,
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
			'slug' => "slug",
		     'image' => "stripUrl",
		]);

		return $purified;

	}

}
