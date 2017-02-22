<?php

/*
|--------------------------------------------------------------------------
| Shortcuts
|--------------------------------------------------------------------------
| These are shortcuts of the site models and modules
*/

function getLocale()
{
	return \Illuminate\Support\Facades\App::getLocale();
}

function user()
{
	if(Auth::check())
		return Auth::user() ;
	else
		return new \App\Models\User() ;
}

/**
 * Easier way to call settings with super-minimal parameters (language=auto, cache=on, default=no etc.)
 * @param $slug
 * @return array|bool|mixed
 */
function getSetting($slug)
{
	return setting($slug)->gain() ;
}

/**
 * a shortcut to fire a chain command to receive setting value
 * @param $slug
 * @return \App\Models\Setting
 */
function setting($slug)
{
	return \App\Models\Setting::builder($slug);
}

function pd($string)
{
	return \App\Providers\AppServiceProvider::pd($string) ;
}


/*
|--------------------------------------------------------------------------
| Additional Helper Functions
|--------------------------------------------------------------------------
| These are the ones used to handle expressions and strings, fully
| independent of the other modules.
*/


/**
 * Compares the given $array with the provided $defaults to fill any unset value, based on the $defaults pattern
 * @param $array
 * @param $defaults
 */
function array_default($array, $defaults)
{
	foreach($defaults as $key => $value) {
		if(!array_has($array, $key))
			$array[$key] = $value;
	}

	return $array;
}

/**
 * Normalizes the givn $array with the provided $reference, by deleting the extra entries and filling unset ones
 * @param $array
 * @param $reference
 * @return array
 */
function array_normalize($array, $reference)
{
	$result = [];
	foreach($reference as $key => $value) {
		if(!array_has($array, $key))
			$result[$key] = $value;
		else
			$result[$key] = $array[$key];
	}

	return $result;

}

function isJson($string) {
	json_decode($string);
	return  (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Says $anything in an acceptable array format, for the debugging purposes.
 * @param $anything
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
function ss($anything)
{
	echo view('templates.say' , ['array'=>$anything]);

}


function v0()
{
	return "javascript:void(0)" ;
}