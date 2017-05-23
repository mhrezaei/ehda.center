<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pack extends Model
{
	use TahaModelTrait , SoftDeletes ;

	public static $reserved_slug = 'root,admin' ;
	public static $meta_fields = ['image' , 'locale_titles'] ;

	protected $guarded         = ['id'];
	private   $cached_posttype = false;

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function unit()
	{
		return $this->belongsTo('App\Models\Unit');
	}

	public function goods()
	{
		return $this->hasMany('App\Models\Good') ;
	}

	public function posttype()
	{
		$posttype = Posttype::findBySlug($this->type);

		if($posttype) {
			return $posttype;
		}
		else {
			return new Posttype();
		}
	}

	public function getPosttypeAttribute()
	{
		if(!$this->cached_posttype) {
			$this->cached_posttype = $this->posttype();
		}

		return $this->cached_posttype;
	}

	public function setPosttype($model)
	{
		$this->cached_posttype = $model;
	}


	/*
	|--------------------------------------------------------------------------
	| Assessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/

	public function getEncryptedTypeAttribute()
	{
		return $this->posttype->encrypted_slug;
	}

	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/
	public function titleIn($locale = 'fa')
	{
		if($locale == 'fa') {
			return $this->title;
		}
		else {
			return $this->spreadMeta()->locale_titles["title-$locale"] ;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public function unitsCombo()
	{
		return Unit::all();
	}

}
