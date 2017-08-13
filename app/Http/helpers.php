<?php

/*
|--------------------------------------------------------------------------
| Shortcuts
|--------------------------------------------------------------------------
| These are shortcuts of the site models and modules
*/

use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Morilog\Jalali\jDate;
use Vinkla\Hashids\Facades\Hashids;


function getLocale()
{
	return \Illuminate\Support\Facades\App::getLocale();
}

function user($id=0)
{
	if($id) {
		return \App\Models\User::find($id);
	}
	else {
		if(Auth::check()) {
			return Auth::user();
		}
		else {
			return new \App\Models\User();
		}
	}
}

/**
 * Easier way to call settings with super-minimal parameters (language=auto, cache=on, default=no etc.)
 *
 * @param $slug
 *
 * @return array|bool|mixed
 */
function getSetting($slug)
{
	return setting($slug)->gain();
}

/**
 * a shortcut to fire a chain command to receive setting value
 *
 * @param $slug
 *
 * @return \App\Models\Setting
 */
function setting($slug = null)
{
	return \App\Models\Setting::builder($slug);
}

/**
 * convert english digits to persian digits
 *
 * @param $string
 *
 * @return mixed
 */
function pd($string)
{
	$farsi_chars = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '۴', '۵', '۶', 'ی', 'ک', 'ک',];
	$latin_chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '٤', '٥', '٦', 'ي', 'ك', 'ك',];

	return str_replace($latin_chars, $farsi_chars, $string);
}

/**
 * convert persian digits to english digits
 *
 * @param $string
 *
 * @return mixed
 */
function ed($string)
{
	$farsi_chars = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٤', '٥', '٦'];
	$latin_chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '4', '5', '6'];

	return str_replace($farsi_chars, $latin_chars, $string);
}

/**
 * smart convert digits depending on site locale
 *
 * @param $string
 *
 * @return mixed
 */
function ad($string , $default_language=false)
{
	if(isLangRtl($default_language)) {
		return pd($string);
	}
	else {
		return ed($string);
	}
}

function isLangRtl($default_language=false)
{
	$rtl_languages = ['fa', 'ar'];

	if(!$default_language) {
		$default_language = getLocale() ;
	}

	if(in_array($default_language, $rtl_languages)) {
		return true;
	}
	else {
		return false ;
	}

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
 *
 * @param $array
 * @param $defaults
 */
function array_default($array, $defaults)
{
	foreach($defaults as $key => $value) {
		if(!array_has($array, $key)) {
			$array[ $key ] = $value;
		}
	}

	return $array;
}

/**
 * Normalizes the given $array with the provided $reference, by deleting the extra entries and filling unset ones
 *
 * @param $array
 * @param $reference
 *
 * @return array
 */
function array_normalize($array, $reference)
{
	$result = [];
	foreach($reference as $key => $value) {
		if(!array_has($array, $key)) {
			$result[ $key ] = $value;
		}
		else {
			$result[ $key ] = $array[ $key ];
		}
	}

	return $result;

}

/**
 * Normalizes the given $array with the provided $reference, by filling unset ones.
 * This functions keeps extra entries.
 *
 * @param $array
 * @param $reference
 *
 * @return array
 */
function array_normalize_keep_originals($array, $reference)
{
	foreach($reference as $key => $value) {
		if(!array_has($array, $key)) {
			$array[ $key ] = $value;
		}
	}

	return $array;
}

function array_maker($string, $first_delimiter = '-', $second_delimiter = '=')
{
	$array = explode($first_delimiter, str_replace(' ', null, $string));
	foreach($array as $key => $switch) {
		$switch = explode($second_delimiter, $switch);
		unset($array[ $key ]);
		if(sizeof($switch) < 2) {
			continue;
		}
		$array[ $switch[0] ] = $switch[1];
	}

	return $array;

}

function array_random($array)
{
	$key = rand(0, sizeof($array) - 1);

	return $array[ $key ];
}

function array_has_required($required, $array)
{
	return arrayHasRequired($required, $array);
}

function arrayHasRequired($required, $array)
{
	if(!is_array($required)) {
		$required = [$required];
	}

	foreach($required as $fieldName) {
		if(!isset($array[ $fieldName ]) or !$array[ $fieldName ]) {
			return false;
		}
	}

	return true;
}

function isJson($string)
{
	json_decode($string);

	return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Says $anything in an acceptable array format, for the debugging purposes.
 *
 * @param $anything
 *
 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
 */
function ss($anything)
{
	echo view('templates.say', ['array' => $anything]);

	return null;
}


function v0()
{
	return "javascript:void(0)";


}

function makeDateTimeString($date, $hour = 0, $minute = 0, $seccond = 0)
{
	$date   = "$date $hour:$minute:$seccond";
	$carbon = new Carbon($date);

	return $carbon->toDateTimeString();

}

function url_locale($url_string = '')
{
	return url('/' . getLocale() . '/' . $url_string);
}

/**
 * Generate the URL to a named route with current language.
 *
 * @param  string $name
 * @param  array  $parameters
 * @param  bool   $absolute
 *
 * @return string
 */
function route_locale($name, $parameters = [])
{
    return app('url')->route($name, $parameters + ['lang' => getLocale()], true);
}

function login($id) //@TODO: Remove this function on production
{
	\Illuminate\Support\Facades\Auth::loginUsingId($id);

	return user()->full_name;
}

function echoDate($date, $foramt = 'default', $language = 'auto', $pd = false)
{
	/*-----------------------------------------------
	| Safety Bypass ...
	*/
	if(in_array($date, [null, '0000-00-00 00:00:00', '0000-00-00'])) {
		return '-';
	}

	/*-----------------------------------------------
	| Process ...
	*/
	if($foramt == 'default') {
		$foramt = 'j F Y [H:m]';
	}

	if($language == 'auto') {
		$language = getLocale();
	}

	switch ($language) {
		case 'fa':
			$date = jDate::forge($date)->format($foramt);
			break;

		case 'en':
			$date = $date->format($foramt);
			break;

		default:
			$date = $date->format($foramt);
	}

	if($pd) {
		return pd($date);
	}
	else {
		return $date;
	}
}

function fakeDrawingCode($amount = false, $timestamp = false) //@TODO: Remove this on production
{
	if(!$timestamp) {
		$timestamp = time();
	}
	if(!$amount) {
		$amount = rand(5, 150) * 10000;
	}

	return \App\Providers\DrawingCodeServiceProvider::create_uniq($timestamp, $amount);
}


function model($class_name, $id = 0)
{
	$class = '\App\Models\\' . studly_case($class_name);

	if(!$id) {
		return $class;
	}

	if(is_string($id)) {
		$id = hashid($id , 'ids') ;
	}

	if(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($class))) {
		$object = $class::withTrashed()->find($id);
	}
	else {
		$object = $class::find($id);
	}

	return $object;
}


function fakeData()
{
	for($i = 1; $i <= 1000; $i++) {
		\App\Models\Drawing::create([
			'user_id'    => 100 + $i,
			'post_id'    => 65,
			'amount'     => rand(1000, 1000000000),
			'lower_line' => rand(1, 100),
			'upper_line' => rand(1000, 5000),
		]);
	}
}


function url_exists($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if($code == 200) {
		$status = true;
	}
	else {
		$status = false;
	}
	curl_close($ch);

	return $status;

}

/**
 * @param delimiter $string
 * @param string    $string
 */
function explodeNotEmpty($delimiter, $string)
{
	return array_values(array_filter(explode($delimiter, $string)));
}

function arrayPrefixToIndex($delimiter, $haystack)
{
	if(is_array($haystack)) {
		foreach($haystack as $index => $field) {
			$parts = explode($delimiter, $field, 2);
			if(count($parts) == 2) {
				$key   = $parts[0];
				$value = $parts[1];
				$value = arrayPrefixToIndex($delimiter, $value);

				$target = &$haystack[ $key ];
				if(isset($target)) {
					if(!is_array($target)) {
						$target = [$target];
					}

					if(!is_array($value)) {
						$value = [$value];
					}

					$target = array_merge($target, $value);
				}
				else {
					$target = $value;
				}
			}
			unset($haystack[ $index ]);
		}

		return $haystack;
	}

	if(is_string($haystack)) {
		$parts = explode($delimiter, $haystack, 2);
		if(count($parts) == 2) {
			$key   = $parts[0];
			$value = $parts[1];
			$value = arrayPrefixToIndex($delimiter, $value);

			$haystack = [
				$key => $value,
			];

			return $haystack;
		}
	}

	return $haystack;
}

function hashid_encrypt($id, $connection = 'main')
{
	return Hashids::connection($connection)->encode($id);
}

function hashid_decrypt($hash, $connection = 'main')
{
    return Hashids::connection($connection)->decode($hash);
}

function hashid_decrypt0($hash, $connection = 'main')
{
	$result = hashid_decrypt($hash, $connection ) ;
	if(isset($result[0])) {
		return $result[0] ;
	}
	else {
		return false ;
	}
}

function hashid($string , $connection= 'main')
{
	if(is_numeric($string)) {
		return hashid_encrypt($string , $connection) ;
	}
	else {
		return hashid_decrypt0($string , $connection) ;
	}
}

/**
 * checks if $code is a valid color code
 *
 * @param $code
 *
 * @return bool
 */
function validateColorCode($code)
{
	if(is_string($code)) {
		if(!starts_with($code, '#')) {
			$code = '#' . $code;
		}


		if(preg_match('/^#[a-fA-F0-9]{6}$/i', $code)) {
			return $code;
		}
	}

	return false;
}

/**
 * @param string $domain
 */
function setDomain($domain = 'global')
{
	session()->put('domain', $domain);

}

/**
 * @return mixed|null
 */
function getDomain()
{
    if (session()->has('domain')) {
        $domain = session()->get('domain');
        if ($domain == 'global') {
            return null;
        } else {
            return $domain;
        }
    } else {
        return null;
    }
}

/**
 * Returns an array including "global" and current domain (in specified)
 *
 * @return array
 */
function getUsableDomains()
{
    $domains = ['global'];

    $currentDomain = getDomain();
    if ($currentDomain) {
        $domains[] = $currentDomain;
    }

    return $domains;
}

function findUser($username, $as_role = null, $username_field = 'auto')
{
	return userFinder($username, $as_role = null, $username_field = 'auto');
}

function userFinder($username, $as_role = null, $username_field = 'auto')
{
	return model('user')::finder($username, $as_role, $username_field);
}

function formatPhone($data)
{
	if(substr($data,0,1) == 0 ) {
		$data = substr($data,1,10);
	}
	return '('.substr($data, 0, 3).') '.substr($data, 3, 3).'-'.substr($data,6) ;
}


/**
 * Returns current url in given locale
 *
 * @param string $locale
 *
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function localeLink($locale)
{
    $route = request()->route();
    $route->setParameter('lang', $locale);

    $forcedParameters = \App\Providers\TransServiceProvider::getForcedUrlParameters($locale);


    if ($forcedParameters === false) {
        return url($locale);
    }

    foreach ($forcedParameters as $key => $value) {
        $route->setParameter($key, $value);
    }

    $parameters = $route->parameters();
    $uri = $route->uri();

    foreach ($parameters as $key => $value) {
        $uri = preg_replace("/{{$key}\??}/", $value, $uri);
    }

    return url($uri);
}

/**
 * Adds sub domain to a given url string.
 *
 * @param string $url
 * @param string $subDomain
 *
 * @return string
 */
function addSubDomain($url, $subDomain)
{
    $parts = parse_url($url);
    $hotsParts = explode('.', $parts['host']);
    array_unshift($hotsParts, $subDomain);
    $parts['host'] = implode('.', $hotsParts);
    return unparse_url($parts);
}

/**
 * Unparse url and returns a url string from a parsed url
 *
 * @param array $parsed_url
 * @param array $ommit
 *
 * @return string
 */
function unparse_url($parsed_url, $ommit = array())
{
    //From Open Web Analytics owa_lib.php

    $url = '';

    $p = array();

    $p['scheme'] = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';

    $p['host'] = isset($parsed_url['host']) ? $parsed_url['host'] : '';

    $p['port'] = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';

    $p['user'] = isset($parsed_url['user']) ? $parsed_url['user'] : '';

    $p['pass'] = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';

    $p['pass'] = ($p['user'] || $p['pass']) ? $p['pass'] . "@" : '';

    $p['path'] = isset($parsed_url['path']) ? $parsed_url['path'] : '';

    $p['query'] = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';

    $p['fragment'] = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';

    if ($ommit) {
        foreach ($ommit as $key) {
            if (isset($p[$key])) {
                $p[$key] = '';
            }
        }
    }

    return $p['scheme'] . $p['user'] . $p['pass'] . $p['host'] . $p['port'] . $p['path'] . $p['query'] . $p['fragment'];
}