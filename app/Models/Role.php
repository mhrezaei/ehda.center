<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
	use TahaModelTrait , SoftDeletes ;

	public static $reserved_slugs = 'root,super' ;
	protected $guarded = ['id'] ;


	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/
	public function users()
	{
		return $this->belongsToMany('App\Models\User')->withTimestamps();;
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getStatusAttribute()
	{
		if($this->trashed())
			return 'inactive' ;
		else
			return 'active' ;
	}


}
