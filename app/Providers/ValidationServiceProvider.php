<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use App\Providers\SecKeyServiceProvider; //@TODO: Bring it here to this project!


class ValidationServiceProvider extends ServiceProvider
{
	private $input;
	private $rules;
	private static $default_global_rules = 'stripArabic';

	public static function purifier($input, $rules , $global_rules = '--default--')
	{
		if($global_rules == '--default--')
			$global_rules = self::$default_global_rules ;

		$ME = new ValidationServiceProvider(app());

		$result = $ME->fire($input, $rules , $global_rules);

		return $result;
	}

	private function addGlobalRules($global_rules)
	{
		foreach($this->input as $key => $data) {
			if($key[0] == '_')
				continue ;

			if(isset($this->rules[$key]))
				$this->rules[$key] .= '|'.$global_rules ;
			else
				$this->rules[$key] = $global_rules ;
		}
	}

	public function fire($input, $rules , $global_rules)
	{
		$this->input = $input;
		$this->rules = $rules;


		$this->addGlobalRules($global_rules) ;
		//		Session::push('test' , $this->rules) ;

		foreach($input as $varName => $data) {
			$this->process($varName);
		}
		return $this->input;
	}

	private function process($key)
	{
		//Interlock...
		if(!isset($this->rules[$key]))
			return;

		//Process...
		$rules = explode('|', $this->rules[$key]);
		array_push($rules , 'stripArabic') ;
		foreach($rules as $rule) {
			$this->applyFilter($key, $rule);
			//			Session::push('test', $key.': '.$rule);
		}

	}

	private function applyFilter($key, $rule)
	{
		$data = $this->input[$key];
		switch ($rule) {
			case "url":
				$data = urldecode($data);
				break;

			case 'slug':
				$data = strtolower(str_slug($data)) ;
				break;

			case 'stripArabic': //persian characters
				$data = str_replace("ي", "ی", $data);
				$data = str_replace("ك", "ک", $data);
				$data = str_replace("ك", "ک", $data);
				$data = str_replace("٤", "۴", $data);
				$data = str_replace("٦", "۶", $data);
				$data = str_replace("٥", "۵", $data);
				break;

			case 'stripMask' :
				$data = str_replace("_" , null , $data) ;
				$data = str_replace("-" , null , $data) ;
				break;

			case "pd":
				$data = str_replace("1", "۱", $data);
				$data = str_replace("2", "۲", $data);
				$data = str_replace("3", "۳", $data);
				$data = str_replace("4", "۴", $data);
				$data = str_replace("5", "۵", $data);
				$data = str_replace("6", "۶", $data);
				$data = str_replace("7", "۷", $data);
				$data = str_replace("8", "۸", $data);
				$data = str_replace("9", "۹", $data);
				$data = str_replace("0", "۰", $data);

				$data = str_replace("ي", "ی", $data);
				$data = str_replace("ك", "ک", $data);
				$data = str_replace("ك", "ک", $data);
				$data = str_replace("٤", "۴", $data);
				$data = str_replace("٦", "۶", $data);
				$data = str_replace("٥", "۵", $data);
				break;

			case "ed":
				$data = str_replace("۱", "1", $data);
				$data = str_replace("۲", "2", $data);
				$data = str_replace("۳", "3", $data);
				$data = str_replace("۴", "4", $data);
				$data = str_replace("۵", "5", $data);
				$data = str_replace("۶", "6", $data);
				$data = str_replace("۷", "7", $data);
				$data = str_replace("۸", "8", $data);
				$data = str_replace("۹", "9", $data);
				$data = str_replace("۰", "0", $data);

				$data = str_replace("٤", "4", $data);
				$data = str_replace("٦", "6", $data);
				$data = str_replace("٥", "5", $data);
				break;

			case "upper":
				$data = strtoupper($data);
				break;

			case "lower":
				$data = strtolower($data);
				break;

			case "number":
			case "numeric" :
				if($data)
					$data = floatval( str_replace(',',null,$data) );
				break;

			case "bool":
				if($data)
					$data = true;
				else
					$data = false;
				break;

			case 'decrypt' :
				$data = Crypt::decrypt($data) ;
				break;

			case 'shetab' :
				$data = str_replace(' - ',null , $data) ;
				break;

			case 'date+1s' :
				$data .= ' 00:00:01' ;
			//				break ;

			case 'date' :
				$carbon = new Carbon($data) ;
				$data = $carbon->toDateTimeString();
				break ;

			case 'time' :
				if(strlen($data)==4)
					$data = $data[0].$data[1].':'.$data[3].$data[4] ;
				break;

			case 'stripUrl' :
				$data = str_replace(url('') , null , $data);
				break;

			case 'sheba' :
				if(!str_contains($data,'IR'))
					$data = "IR".$data ;
				break;

			case 'array' :
				$data = array_filter(explode(',' , $data)) ;
				break;
		}

		$this->input[$key] = $data;
	}

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->app['validator']->extend('captcha', function ($attribute, $value, $parameters) {
			return SecKeyServiceProvider::checkAnswer($value, $parameters[0]);
		});
		$this->app['validator']->extend('phone', function ($attribute, $value, $parameters, $validator) {
			return self::validatePhoneNo($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('forbidden_chars', function ($attribute, $value, $parameters, $validator) {
			return self::validateForbiddenChars($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('required_chars', function ($attribute, $value, $parameters, $validator) {
			return self::validateRequiredChars($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('code_melli', function ($attribute, $value, $parameters, $validator) {
			return self::validateCodeMelli($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('national_id', function ($attribute, $value, $parameters, $validator) {
			return self::validateNationalId($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('postal_code', function ($attribute, $value, $parameters, $validator) {
			return self::validatePostalCode($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('sheba', function ($attribute, $value, $parameters, $validator) {
			return self::validateSheba($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('persian', function($attribute, $value, $parameters, $validator){
			return self::persianChar($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('english', function($attribute, $value, $parameters, $validator){
			return self::englishChar($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('fileExists', function($attribute, $value, $parameters, $validator){
			return self::fileExists($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('time', function($attribute, $value, $parameters, $validator){
			return self::validateTime($attribute, $value, $parameters, $validator);
		});
		$this->app['validator']->extend('shetab', function($attribute, $value, $parameters, $validator){
			return self::validateShetab($attribute, $value, $parameters, $validator);
		});
	}

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Rules
	|--------------------------------------------------------------------------
	| All private static functions
	*/

	private function validateShetab($attribute, $value, $parameters, $validator)
	{
		return strlen($value) == 16 ;
	}

	private function validateTime($attribute, $value, $parameters, $validator)
	{
		if(strlen($value) != 5 or $value[2] != ':')
			return false ;

		$array = explode(':',$value) ;
		$h = floatval($array[0]);
		$m = floatval($array[1]);

		if($h<0 or $h>23 or $m<0 or $m>59)
			return false ;
		else
			return true ;



	}

	private function fileExists($attribute, $value, $parameters, $validator)
	{
		//@TODO: Doesn't work on remote
		$value = str_replace(url(),null,$value);
		if (file_exists($value))
			return true ;
		else
			return false ;
	}
	private static function validateSheba($attribute, $value, $parameters, $validator)
	{
		return true ; //@TODO: write this!
	}

	private static function validatePhoneNo($attribute, $value, $parameters, $validator)
	{
		$mood = $parameters[0];

		if(strlen($value) != 11)
			return false;
		if(substr($value, 0, 1) != '0')
			return false;
		if(!ctype_digit($value))
			return false;

		switch ($mood) {
			case "mobile" :
				if(substr($value, 1, 1) != '9')
					return false;
				break;

			case "fixed" :
				break;
		}

		return true;

	}

	private static function validateNationalId($attribute, $value, $parameters, $validator)
	{
		return true ; //@TODO: Remove this line on production!
		if(strlen($value) != 11 or !is_numeric($value))
			return false ;

		if(intval(substr($value , 3 , 6)) == 0)
			return false ;

		$c = intval(substr($value,10,1)) ;
		$d = intval(substr($value,9,1)) + 2 ;
		$z = [29,27,23,19,17] ;
		$s = 0 ;

		for($i=0 ; $i<10 ; $i++) {
			$s += ($d + intval(substr($value,$i,1))) * $z[$i%5] ;
		}

		$s = $s % 11 ;
		if($s==10)
			$s = 0 ;

		return $c==$s ;

	}
	private static function validatePostalCode($attribute, $value, $parameters, $validator)
	{
		if(strlen($value) != 10 or !is_numeric($value))
			return false ;

		if(str_contains($value , '2') or str_contains($value , '0'))
			return false ;

		return true ;
	}
	private static function validateForbiddenChars($attribute, $value, $parameters, $validator)
	{
		return !str_contains($value , $parameters) ;
	}
	private static function validateRequiredChars($attribute, $value, $parameters, $validator)
	{
		foreach($parameters as $parameter) {
			if(!str_contains($value,$parameter))
				return false ;
		}

		return true ;
	}

	private static function validateCodeMelli($attribute, $value, $parameters, $validator)
	{
		if(!preg_match("/^\d{10}$/", $value)) {
			return false;
		}

		$check = (int)$value[9];
		$sum = array_sum(array_map(function ($x) use ($value) {
				return ((int)$value[$x]) * (10 - $x);
			}, range(0, 8))) % 11;

		return ($sum < 2 && $check == $sum) || ($sum >= 2 && $check + $sum == 11);
	}

	private static function uniord($u)
	{
		$k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
		$k1 = ord(substr($k, 0, 1));
		$k2 = ord(substr($k, 1, 1));
		return $k2 * 256 + $k1;
	}

	private static function englishChar($attribute, $value, $parameters, $validator)
	{
		return preg_match('/^[a-zA-Z0-9_\-]/', $value);
	}

	private static function persianChar($attribute, $value, $parameters, $validator) {
		$str = $value;
		if(isset($parameters[0]))
			$percent = $parameters[0];
		else
			$percent = 70 ;
		if(mb_detect_encoding($str) !== 'UTF-8')
		{
			$str = mb_convert_encoding($str,mb_detect_encoding($str),'UTF-8');
		}
		preg_match_all('/.|\n/u', $str, $matches);
		$chars = $matches[0];
		$arabic_count = 0;
		$latin_count = 0;
		$total_count = 0;
		foreach($chars as $char) {
			$pos = self::uniord($char);

			if($pos >= 1536 && $pos <= 1791) {
				$arabic_count++;
			} else if($pos > 123 && $pos < 123) {
				$latin_count++;
			}
			$total_count++;
		}

		if(($arabic_count/$total_count) > ($percent / 100))
		{
			// 60% arabic chars, its probably arabic
			return true;
		}
		else
		{
			return false;
		}
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
}
