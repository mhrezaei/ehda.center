<?php

namespace App\Providers;

use Asanak\Sms\Facade\AsanakSms;
use Illuminate\Support\ServiceProvider;


class SmsServiceProvider extends ServiceProvider
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

	public static function send($destination , $msgBody)
	{
		return AsanakSms::send($destination , $msgBody);
	}

}
