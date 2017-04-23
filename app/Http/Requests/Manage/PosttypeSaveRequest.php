<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Posttype;
use App\Providers\ValidationServiceProvider;
use Illuminate\Support\Facades\Auth;


class PosttypeSaveRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true ;
		//Permission is checked from the Route
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$input = $this->all();
		$id = $input['id'] ;
		return [
			'title' => 'required|unique:posttypes,title,'.$id,
			'singular_title' => 'required',
			'slug' => 'required|alpha_dash|forbidden_chars:_|not_in:'.Posttype::$reserved_slugs.'|unique:posttypes,slug,'.$id.',id',
			'template'=>'required|in:'.implode(',',Posttype::$available_templates) ,
			'icon' => 'required'
		];

	}

	public function all()
	{
		$value	= parent::all();
		$purified = ValidationServiceProvider::purifier($value,[
			'slug'  =>  'lower',
			'template' => 'lower' ,
			'features' => 'lower' ,
			'meta_fields' => 'lower' ,
			'icon' => 'lower' ,
			'order' => "ed",
		]);
		return $purified;

	}

}
