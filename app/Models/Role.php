<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
	use TahaModelTrait, SoftDeletes;

	public static $reserved_slugs = 'root,super,user,all,dev,developer';
	public static $meta_fields    = ['icon'];
	protected     $guarded        = ['id'];


	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/

	public function users()
	{
		return $this->belongsToMany('App\Models\User')->withTimestamps();
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getStatusAttribute()
	{
		if($this->trashed()) {
			return 'inactive';
		}
		else {
			return 'active';
		}
	}

	public function getMenuIconAttribute()
	{
		if($this->spreadMeta()->icon) {
			return $this->icon;
		}
		else {
			return 'user';
		}
	}

	public function getHasModulesAttribute()
	{
		return isJson($this->modules);
	}

	public function getModulesArrayAttribute()
	{
		if(!$this->has_modules) {
			return [] ;
		}
		else {
			return json_decode($this->modules , true);
		}
	}


	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public static function checkManagePermission($role_slug, $criteria)
	{
		if(in_array($role_slug , ['admin','all'])){
			return user()->isSuper() ;
		}
		switch($criteria) {
			case 'bin' :
			case 'banned' :
				$permit = 'bin' ;
				break;

			default:
				$permit = '*' ;
		}

		return user()->as('admin')->can("users-$role_slug.$permit");

	}

}
