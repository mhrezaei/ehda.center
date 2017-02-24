<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use TahaModelTrait , SoftDeletes ;

	protected $guarded = ['id'] ;
	public static $reserved_slugs = 'root,admin' ;
	public static $meta_fields = ['image'];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function folder()
	{
		return $this->belongsTo('App\Models\Folder');
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/


}
