<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Unit extends Model
{
	use TahaModelTrait, SoftDeletes;
	public static $reserved_slugs = 'root,admin';
	protected     $guarded        = ['id'];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function goods()
	{
		return $this->belongsToMany('App\Models\Good') ;
	}
	public function packs()
	{
		return $this->belongsToMany('App\Models\Pack') ;
	}

	/*
	|--------------------------------------------------------------------------
	| Assessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/



	public function getStatusAttribute()
	{
		if($this->trashed()){
			$status = 'inactive' ;
		}
		else {
			$status = 'active' ;
		}

		return $status ;
	}

}
