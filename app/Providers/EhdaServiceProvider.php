<?php

namespace App\Providers;

use App\Models\Printing;
use Illuminate\Support\ServiceProvider;


class EhdaServiceProvider extends ServiceProvider
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

	public static function getExcelExport()
	{
		$event_id = session()->get('excel_event_id');

		return Printing::selector([
			'event_id' => $event_id,
			'criteria' => "under_excel_printing",
		])->get()
			;
	}

}
