<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Activity extends Model
{
	use TahaModelTrait, SoftDeletes;

	public static $reserved_slugs = 'admin,root';
	protected $guarded = ['id'] ;

	public static function slugToCaption($slug)
	{
		if(is_array($slug)) {
			$result = [] ;
			foreach($slug as $item) {
				$caption = self::slugToCaption($item) ;
				if($caption) {
					$result[] = $caption ;
				}
			}
			return $result ;
		}
		else {
			$model = self::findBySlug($slug) ;
			if(!$model or !$model->id) {
				return false ;
			}
			else {
				return $model->title ;
			}

		}
	}

	public static function sortedAll()
	{
		return self::orderBy('title')->get() ;
	}

	public static function requestToString($request, $prefix = '_activity-')
	{
		/*-----------------------------------------------
		| Array Conversion ...
		*/
		if(is_array($request)) {
			$data = $request;
		}
		else {
			$data = $request->toArray();
		}



		/*-----------------------------------------------
		| Loop ...
		*/
		$result = null ;
		foreach($data as $key => $value) {
			if(str_contains($key , $prefix)) {
				if($value) {
					$result .= str_replace($prefix , null , $key) ."," ;
				}
			}
		}

		/*-----------------------------------------------
		| Return ...
		*/
		return $result ;


	}

}
