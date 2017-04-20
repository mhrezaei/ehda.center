<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Drawing extends Model
{
	protected     $guarded       = ['id'];
	public $timestamps = false ;

	public function user()
	{
		return $this->belongsTo('App\Models\User') ;
	}

	public static function prepareDatabase($post)
	{
		self::truncate() ;
		Receipt::withTrashed()->update([
			'operation_integer' => 0,
		]);
		$post->receipts->groupBy('user_id')->update([
			'operation_integer' => 1,
		]) ;
		session()->put('line_number','0');

	}

	public static function pull($number)
	{
		return self::where('lower_line', '<=', $number)->where('upper_line', '>=', $number)->first();
	}
}
