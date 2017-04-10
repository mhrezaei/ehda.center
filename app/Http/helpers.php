<?php

/*
|--------------------------------------------------------------------------
| Shortcuts
|--------------------------------------------------------------------------
| These are shortcuts of the site models and modules
*/

use Carbon\Carbon;
use Morilog\Jalali\jDate;

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
function setting($slug=null)
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
 * Normalizes the given $array with the provided $reference, by deleting the extra entries and filling unset ones
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

function array_maker($string , $first_delimiter = '-' , $second_delimiter = ':')
{
	$array = explode($first_delimiter,str_replace(' ',null, $string));
	foreach($array as $key => $switch) {
		$switch = explode($second_delimiter , $switch) ;
		unset($array[$key]);
		if(sizeof($switch)<2) {
			continue;
		}
		$array[ $switch[0] ] = $switch[1] ;
	}

	return $array ;

}

function array_random($array)
{
	$key = rand(0,sizeof($array)-1);
	return $array[$key];
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
	return null ;
}


function v0()
{
	return "javascript:void(0)" ;


}

function makeDateTimeString($date , $hour=0 , $minute=0 , $seccond=0)
{
	$date = "$date $hour:$minute:$seccond";
	$carbon = new Carbon($date) ;
	return $carbon->toDateTimeString() ;

}

function url_locale($url_string = '')
{
    return url('/' . getLocale() . '/' . $url_string);
}

function login($id) //@TODO: Remove this function on production
{
	\Illuminate\Support\Facades\Auth::loginUsingId($id);
	return user()->full_name ;
}
function echoDate($date , $foramt='default' , $language='auto', $pd = false)
{
    if ($foramt == 'default')
    {
        $foramt = 'j F Y [H:m]';
    }

    if ($language == 'auto')
    {
        $language = getLocale();
    }

    switch ($language)
    {
        case 'fa':
            $date = jDate::forge($date)->format($foramt);
            break;

        case 'en':
            $date = $date->format($foramt);
            break;

        default:
            $date = $date->format($foramt);
    }

    if ($pd)
    {
        return pd($date);
    }
    else
    {
        return $date;
    }
}

function fakeDrawingCode($amount = false , $timestamp = false) //@TODO: Remove this on production
{
	if(!$timestamp) $timestamp = time() ;
	if(!$amount) $amount = rand(5,150) * 10000 ;

	return \App\Providers\DrawingCodeServiceProvider::create_uniq($timestamp , $amount) ;
}