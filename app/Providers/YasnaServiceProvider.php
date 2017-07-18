<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class YasnaServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Checks if a given string is compatible with a valid Iranian phone_number format or not.
	 *
	 * @param $value
	 * @param $mood , accepts null, mobile, fixed
	 *
	 * @return bool
	 */
	public static function isPhoneNumber($value, $mood = null)
	{
		if(strlen($value) != 11) {
			return false;
		}
		if(substr($value, 0, 1) != '0') {
			return false;
		}
		if(!ctype_digit($value)) {
			return false;
		}

		switch ($mood) {
			case "mobile" :
				if(substr($value, 1, 1) != '9') {
					return false;
				}
				break;

			case "fixed" :
				break;
		}

		return true;

	}


	/**
	 * Checks if a given string is compatible with a valid code_melli format or not.
	 *
	 * @param $value
	 *
	 * @return bool
	 */
	public static function isCodeMelli($value)
	{
		if(!preg_match("/^\d{10}$/", $value)) {
			return false;
		}

		$check = (int)$value[9];
		$sum   = array_sum(array_map(function ($x) use ($value) {
				return ((int)$value[ $x ]) * (10 - $x);
			}, range(0, 8))) % 11;

		return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
	}
}
