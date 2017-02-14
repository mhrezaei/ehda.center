<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	use TahaModelTrait ;

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/
	public function users()
	{
		return $this->belongsToMany('App\Models\User');
	}

}
