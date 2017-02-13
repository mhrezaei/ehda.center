<?php

namespace App\Models;

use App\Providers\HelperServiceProvider;
use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;

class Setting extends Model
{
	public static $available_data_types = ['text' , 'textarea' , 'boolean' , 'date' , 'photo' , 'array'] ;
	public static $available_categories = ['upstream' , 'socials' , 'contact' , 'template' , 'database'] ;
	public static $full_categories = ['socials' , 'contact' , 'template' , 'database' ] ;
	public static $default_when_not_found = '-' ;
	public static $unset_signals = ['unset' , 'default' , '=' , ''] ;
	public static $reserved_slugs = 'none,setting' ;
	protected $guarded = ['id' , 'default_value'] ;
	public $request_locale = null ;

	use TahaModelTrait ;

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getValueAttribute()
	{
		$value = $this->raw_value ;

		switch($this->data_type) {
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

	public function getRawValueAttribute()
	{
		$value = $this->custom_value ;// Crypt::decrypt($this->custom_value) ;
		$result = null ;

		if($this->is_localized) {
			$value = json_decode($value , true) ;
			if(array_has($value , $this->locale))
				return $value[$this->locale] ;
			else
				return $this->raw_default ;
		}
		else {
			if(!$value)
				return $this->raw_default ;
			else
				return $value ;
		}

	}
	
	public function getRawDefaultAttribute()
	{
		$value = $this->default_value; //Crypt::decrypt($this->default_value) ;
		$result = null ;

		if($this->is_localized) {
			$value = json_decode($value , true) ;
			if(array_has($value , $this->locale))
				return $value[$this->locale] ;
			else
				return $this->default_when_not_found ;
		}
		else {
			if(!$value)
				return $this->default_when_not_found ;
			else
				return $value ;
		}

	}

	public function getSessionKeyAttribute()
	{
		return "setting-" . $this->slug ;
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


	/**
	 * @param       $slug
	 * @param array $para: [locale=auto , fresh=false , formatted=true , $default=false]
	 * @return string
	 */
	public static function get($slug, $para = [])
	{
		extract(HelperServiceProvider::array_normalize($para , [
			'locale' => App::getLocale(),
			'fresh' => false,
			'formatted' => true,
			'default' => false,
		]));

		//Check session...
		if(!$default and !$fresh) {
			$value = session()->get("setting-$slug" , "NO") ;
			if($value != "NO")
				return $value ;
		}


		//Read Data...
		$model = self::where('slug', $slug)->first() ;

		//If not found...
		if(!$model)
			return self::$default_when_not_found ;

		//Normal Situation...
		$model->request_locale = $locale ;
		if($default) {
			$model->custom_value = $model->default_value ;
			$model->is_resident = false ;
		}

		if($formatted)
			$value = $model->value ;
		else
			$value = $model->raw_value ;

		//Session store if required...
		if($model->is_resident) {
			session()->put($model->session_key, $value);
		}

		//Return...
		return $value ;

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
			$trans = "manage.settings.downstream_settings.category.$category" ;
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
		$branches = Branch::selector('category')->get();
		foreach($branches as $branch) {
			array_push($return , [
					'categories/'.$branch->slug ,
					trans('posts.categories.categories_of').' '.$branch->plural_title
			]);
		}
		//		dd($branches);

		return $return ;
	}

	public function dataTypes()
	{
		$return = [] ;
		foreach(self::$available_data_types as $data_type)  {
			$trans = "manage.settings.downstream_settings.data_type.$data_type" ;
			if(Lang::has($trans))
				$caption = trans($trans);
			else
				$caption = $data_type ;
			array_push($return , [$data_type , $caption]) ;
		}

		return $return ;

	}


}
