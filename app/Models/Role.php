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
			case 'search' :
			case 'browse' :
				$permit = 'browse' ;
				break;

			case 'bin' :
			case 'banned' :
				$permit = 'bin' ;
				break;

			default:
				$permit = $criteria ;
		}

		return user()->as('admin')->can("users-$role_slug.$permit");

	}

}
