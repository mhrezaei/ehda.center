<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Domain extends Model
{
	use SoftDeletes , TahaModelTrait ;
	protected  $guarded = ['id'] ;

	public static $reserved_slugs = 'admin,global,iran,ir,manage,all' ;

	public function states()
	{
		return $this->hasMany('App\Models\State') ;
	}
}
