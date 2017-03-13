<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Category extends Model
{
	use TahaModelTrait ;//, SoftDeletes ;

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
	public function getHashIdAttribute()
	{
		return Hashids::encode($this->id);
	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public static function realId($hash_id)
	{
		return Hashids::decode($hash_id)[0];
	}


}
