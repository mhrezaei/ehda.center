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
