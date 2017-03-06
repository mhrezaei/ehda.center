<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
	use TahaModelTrait , SoftDeletes ;
	protected $guarded = ['id'] ;
	public static $reserved_slugs = 'root,admin' ;

}
