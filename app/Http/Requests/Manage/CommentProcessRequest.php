<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Posttype;
use App\Providers\ValidationServiceProvider;
use Illuminate\Support\Facades\Auth;


class CommentProcessRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true ;
		//Permission is to be checked in the controller
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
			'status' => "in:private,published,pending" ,
		];

	}

	public function all()
	{
		$value	= parent::all();
		$purified = ValidationServiceProvider::purifier($value,[
			'reply'  =>  'pd',
		]);
		return $purified;

	}

}
