<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
	use TahaModelTrait , SoftDeletes ;

	protected $guarded = ['id'] ;
	public static $reserved_slugs = 'root,admin' ;
//	public static $meta_fields = [];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function posttype()
	{
		return $this->belongsTo('App\Models\Posttype');
	}

	public function categories()
	{
		return $this->hasMany('App\Models\Category');
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/

}
