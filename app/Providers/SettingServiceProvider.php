<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
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

	}

	public static function get($slug)
	{
		return Setting::take($slug);
	}

	public static function isLocale($lang)
	{
		return App::isLocale($lang);
	}

	public static function getLocale()
	{
		return App::getLocale();
	}

	public static function getUrl($lang)
	{
		return Setting::take('httpd') . $lang . '.' . Setting::get('domain_name');
	}
}
