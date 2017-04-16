<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Morilog\Jalali\Facades\jDateTime;
use Morilog\Jalali\jDate;


class Receipt extends Model
{
	protected $guarded = ['id'];

	use TahaModelTrait , SoftDeletes;



	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function user()
	{
		return $this->belongsTo('App\Models\User') ;
	}

	public function cacheRegenerate()
	{
		$this->user->cacheUpdateReceipts() ;
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors and Mutators
	|-------------------------------------------------------------------------
	|
	*/

	public function getDashedCodeAttribute()
	{
		// separator
		$dashed_code = '';
		$uniq_code   = str_split($this->code);
		for($i = 0; $i <= 19; $i++) {
			if($i == 4 or $i == 9 or $i == 14) {
				$seperator = '-';
			}
			else {
				$seperator = '';
			}
			$dashed_code .= $uniq_code[ $i ] . $seperator;
		}

		return $dashed_code;
	}

	public function getAmountFormatAttribute()
	{
		if($this->purchased_amount > 0) {
			return number_format($this->purchased_amount);
		}
		else {
			return 0;
		}
	}
}
