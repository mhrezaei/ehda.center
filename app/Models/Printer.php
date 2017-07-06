<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Printer extends Model
{
	protected $guarded = ['id'];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
}
