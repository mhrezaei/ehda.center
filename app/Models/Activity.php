<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Activity extends Model
{
	use TahaModelTrait, SoftDeletes;

	public static $reserved_slugs = 'admin,root';
	protected $guarded = ['id'] ;

}
