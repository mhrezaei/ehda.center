<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;

class Setting extends Model
{
	public static $available_data_types = ['text' , 'textarea' , 'boolean' , 'date' , 'photo' , 'array'] ;
	public static $available_categories = ['upstream' , 'socials' , 'contact' , 'template' , 'database'] ;
	public static $full_categories = ['upstream' , 'socials' , 'contact' , 'template' , 'database' ] ;
	public static $default_when_not_found = '-' ;
	public static $unset_signals = ['unset' , 'default' , '=' , ''] ;
	public static $reserved_slugs = 'none,setting' ;
	protected $guarded = ['id' , 'default_value'] ;
	public $request_locale = null ;

	protected $casts = [
			'developers_only' => 'boolean' ,
			'is_resident' => 'boolean' ,
			'is_localized' => 'boolean' ,
	];

	protected $request_language = '';
	protected $request_fresh_reveal = false;
	protected $request_default = false;
	protected $request_unformatted = false ;

	use TahaModelTrait ;

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/

	public function getRawDefaultAttribute()
	{
		$value = $this->default_value; //Crypt::decrypt($this->default_value) ;
		$result = null ;

		if(!$value)
			return $this->default_when_not_found ;
		else
			return $value ;

	}

	public function getSessionKeyAttribute()
	{
		return "setting-" . $this->slug ;
	}

	/*
	|--------------------------------------------------------------------------
	| Save Methods
	|--------------------------------------------------------------------------
	|
	*/
	public function saveRequest($request)
	{
		//Default Value...
		if(user()->isDeveloper()){
			$this->default_value = $this->purify($request->default_value);
		}

		//Custom Value...
		if(!$this->is_localized) {
			$this->custom_value = $this->purify($request->custom_value);
		}
		else {
			$array = [];
			foreach(setting('site_locales')->nocache()->gain() as $lang){
				$array[$lang] = $this->purify($request->toArray()[$lang]);
			}
			$this->custom_value = json_encode($array);
		}

		//Save...
		return $this->save() ;

	}

	protected function purify($value)
	{
		switch($this->data_type)
		{
			case 'boolean':
				$value = booleanValue($value) ;
				break;
			case 'date':
				$carbon = new Carbon($value);
				$value = $carbon->toDateTimeString();
				break;
			case 'photo':
				$value = str_replace(url('') , null , $value);

		}
		return $value ;
	}


	/*
	|--------------------------------------------------------------------------
	| Chain Methods
	|--------------------------------------------------------------------------
	| Pattern: Setting::builder('folan')->in('fa')->nocache()->defaultValue()->raw()->gain()
	| First and last methods are mandatory, while the rest is optional as per required.
	*/
	public static function builder($slug=null)
	{
		$model = new self ;
		$model->ask($slug) ;
		return $model->reset() ;
	}


	public function reset()
	{
		$this->request_language = App::getLocale() ;
		$this->request_language = '';
		$this->request_fresh_reveal = false;
		$this->request_default = false;
		$this->request_unformatted = false ;
		return $this ;
	}

	public function in($language_code)
	{
		$this->request_language = $language_code ;
		return $this ;
	}

	public function noCache()
	{
		$this->request_fresh_reveal = true ;
		return $this ;
	}

	public function defaultValue()
	{
		$this->request_default = true ;
		return $this ;
	}

	public function raw()
	{
		$this->request_unformatted = true ;
		return $this ;
	}

	public function ask($slug)
	{
		$this->slug = $slug ;
		return $this ;
	}

	public function gain($slug=null)
	{
		if($slug) {
			$this->ask($slug) ;
		}
		if(!$this->slug)
			return self::$default_when_not_found ;

		//If already revealed...
		if($this->exists)
			$record = $this ;

		//Look in session...
		else {
			$record = session()->get($this->session_key , "NO") ;
			if($this->request_fresh_reveal or $record=="NO") {
				$record = self::findBySlug($this->slug);
				if(!$record)
					return self::$default_when_not_found ;
				if($record->is_resident)
					session()->put($this->session_key , $record);
			}
		}

		//default...
		if($this->request_default) {
			$value = $record->raw_default;
		}
		else {
			$value = $record->custom_value ;
			if(!$value){
				$value = $record->raw_default ;
			}
		}

		//Locales...
		if($record->is_localized and !$record->request_default) {
			if(isJson($value)){
				$value = json_decode($value , true) ;
				if(array_has($value , $record->request_language))
					$value = $value[$record->request_language] ;
				else
					$value = $record->raw_default ;
			}
			else {
				$value = $record->raw_default ;
			}
		}


		//format...
		if($record->request_unformatted)
			return $value ;

		switch($record->data_type) {
			case 'boolean' :
				return boolval($value) ;

			case 'array' :
				$array = array_filter(preg_split("/\\r\\n|\\r|\\n/",  $value)) ;
				$array = array_sort_recursive($array ); //@TODO: Sort correctly!
				return $array ;

			default:
				return $value;
		}


	}


	/*
	|--------------------------------------------------------------------------
	| Static Set & Get
	|--------------------------------------------------------------------------
	|
	*/

	public static function set($slug, $new_value, $locale = 'auto', $set_for_default = false)
	{
		$model = self::findBySlug($slug);

		//If not found...
		if(!$model)
			return false ;

		//Get Locale...
		if($locale=='auto')
			$locale = App::getLocale() ;

		//Read Data...
		if($model->is_localized) {
			if($set_for_default) {
				$default_value = json_decode($model->default_value , 1);
				$default_value[$locale] = $new_value ;
				$model->default_value = json_encode($default_value);
			}
			else {
				$custom_value = json_decode($model->custom_value , 1);
				$custom_value[$locale] = $new_value ;
				$model->custom_value = json_encode($custom_value);
			}
		}
		else {
			if($set_for_default)
				$model->default_value = $new_value ;
			else
				$model->custom_value = $new_value ;
		}

		//Clear residency...
		session()->forget($model->session_key) ;

		//Save...
		return $model->update() ;
	}

	/*
	|--------------------------------------------------------------------------
	| Helper Functions
	|--------------------------------------------------------------------------
	|
	*/
	public function categories()
	{
		$return = [] ;

		// Real Categories...
		foreach(self::$full_categories as $category)  {
			$trans = "settings.categories.$category" ;
			if(Lang::has($trans))
				$caption = trans($trans);
			else
				$caption = $category ;
			array_push($return , [$category , $caption]) ;
		}

		// Entry Categories...
		array_push($return , [
				'handles' ,
				trans('calendar.handles') ,
		]);

		// Branch Categories...
//		$branches = Branch::selector('category')->get();
//		foreach($branches as $branch) {
//			array_push($return , [
//					'categories/'.$branch->slug ,
//					trans('posts.categories.categories_of').' '.$branch->plural_title
//			]);
//		}

		return $return ;
	}

	public function dataTypes()
	{
		$return = [] ;
		foreach(self::$available_data_types as $data_type)  {
			$trans = "forms.data_type.$data_type" ;
			if(Lang::has($trans))
				$caption = trans($trans);
			else
				$caption = $data_type ;
			array_push($return , [$data_type , $caption]) ;
		}

		return $return ;

	}


}
