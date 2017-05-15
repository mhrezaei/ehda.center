<?php

namespace App\Http\Requests\Manage;

use App\Http\Requests\Request;
use App\Models\Post;
use App\Providers\ValidationServiceProvider;


class PostSaveRequest extends Request
{
	public function authorize()
	{
		return true;
		//will be checked inside the controller, where the model is actually revealed.
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$input = $this->all();
		$rules = [] ;

		switch($input['_submit']) {
			case 'delete' :
			case 'delete_original':
			case 'unpublish' :
				break ;

			case 'publish' :
			case 'approval' :
				$rules = [
					'title' => "required",
//					'slug' => 'english|not_in:'.Post::$reserved_slugs,
				];
				if(isset($input['_schedule']) and $input['_schedule']) {
					$rules = array_merge($rules , [
						'publish_date' => "required|date",
						'publish_hour' => "required|numeric|min:0|max:23",
						'publish_minute' => "required|numeric|min:0|max:59",
					]);
				}
				if(isset($input['price']) and $input['price']) {
					$rules = array_merge($rules , [
						'price' => "numeric",
						'discount_amount' => "numeric|between:0,".$input['price'] ,
						//'sale_price' => "numeric|max:".$input['price'],
						//'sale_expires_date' => "date",
						//'sale_expires_hour' => "numeric|min:0|max:23",
						//'sale_expires_minute' => "numeric|min:0|max:59",
					]);
				}

				foreach($input['_meta_required_fields'] as $field) {
					$rules = array_merge($rules , [
						$field => "required",
					]);
				}
				break;

			case 'reject':
				$rules = [
						'title' => "required",
//						'moderate_note' => "required",
				];
				break;

			case 'save' :
				$rules = [
						'title' => "required",
//						'slug' => "english",
				];
				break;

		}
		return $rules ;

	}

	public function all()
	{
		$value	= parent::all();
		$purified = ValidationServiceProvider::purifier($value,[
			'publish_minute' => "ed",
			'publish_hour' => "ed",
			'price' => "ed|numeric",
			'discount_amount' => "ed|numeric" ,
			//'sale_price' => "ed|numeric",
			//'sale_expires_minute' => "ed",
			//'sale_expires_hour' => "ed",
//			'type' => "decrypt",
			'_meta_required_fields' => "decrypt|array",
			'slug' => "lower",
		]);
		return $purified;

	}

}
