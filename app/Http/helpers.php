<?php

/*
|--------------------------------------------------------------------------
| Shortcuts
|--------------------------------------------------------------------------
| These are shortcuts of the site models and modules
*/


function user()
{
	if(Auth::check())
		return Auth::user() ;
	else
		return false ;
}

/**
 * A shortcut to the public static module "get" from Setting model
 * @param       $slug
 * @param array $para: [locale=auto , fresh=false , formatted=true , $default=false]
 * @return string
 */
function setting($slug , $para=[])
{
	return \App\Models\Setting::get($slug , $para);
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