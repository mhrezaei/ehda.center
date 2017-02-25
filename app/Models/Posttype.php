<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Posttype extends Model
{
	use TahaModelTrait , SoftDeletes;
	protected $guarded = ['id'] ;

	public static $available_features = [
			'text' => ['list' , 'success' , []],
			'long_title' => ['text-height' , 'success' , ['long_title:text']],
			'title2' => ['subscript' , 'success' , ['title2:text']],
			'abstract' => ['compress' , 'success' , ['abstract:text']],
			'featured_image' => ['file-image-o' , 'info' , ['featured_image:photo']],
			'download' => ['download' , 'info' , ['download_file:file']],
			'rss' => ['rss' , 'info' , []],
			'comment' => ['comments-o' , 'info' , []],
			'rate' => ['star-half-o' , 'info' , [] ] , //@TODO: feature_fields
			'album' => ['address-book-o' , 'info'  , ['post_photos:auto']], //@TODO: feature_fields datatype!
			'category' => ['tasks' , 'info' , [] ],
			'keywords' => ['tags' , 'info' , []], //@TODO: feature_fields
			'searchable' => ['search' , 'info' , []],
			'preview' => ['eye' , 'info' , []],
			'seo' => ['stethoscope' , 'info' , ['seo_status:text']],
			'price' => ['dollar' , 'warning' , [ 'sale_price:text' , 'sale_expires_at:text'  , 'package_id:text']],
			'basket' => ['shopping-basket' , 'warning' , []],
			'digest' => ['fire' , 'info' , []],
			'schedule' => ['clock-o' , 'info' , ['original_published_at:auto']],
			'register' => ['user-plus' , 'warning' , []],
			'visibility_choice' => ['shield' , 'warning' , []],
			'template_choice' => ['th-large' , 'warning' , []],
			'slug' => ['hashtag' , 'danger' , []],
			'developers_only' => ['github-alt' , 'danger' , []],
	];
	public static $available_templates = ['album' , 'post' , 'product' , 'slideshow' , 'dialogue' , 'faq'] ;
	public static $available_meta_types = ['text' , 'textarea' , 'date' , 'boolean' , 'photo' , 'file'];
	public static $reserved_slugs = 'root,admin' ;
	public static $meta_fields = ['features' , 'template' , 'feature_meta' , 'optional_meta' , 'visibility', 'singular_title' , 'icon'];
	public static $basement_meta = "moderate_note:text " ;

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function posts()
	{
		return Post::where('title' , $this->slug);
//		return $this->hasMany('App\Models\Post');
	}


	public function folders()
	{
		return $this->hasMany('App\Models\Folder');
	}

	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/
	public static function withFeature($feature)
	{
		$models = self::whereRaw("LOCATE('$feature' , `features`)" )->get() ;

		$result = [] ;
		foreach($models as $model) {
			array_push($result , $model->slug);
		}

		return $result ;
	}

	public static function groups()
	{
		return self::orderBy('header_title' , 'desc')->groupBy('header_title') ;
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getEncryptedSlugAttribute()
	{
		return Crypt::encrypt($this->slug);
	}

	public function getFeaturesArrayAttribute()
	{
		return array_filter(explode(' ', $this->features));
	}

	public function getAvailableFeaturesAttribute()
	{
		return json_encode(self::$available_features) ;
	}

	public function getMetaFieldsAttribute()
	{
		$this->spreadMeta() ;
		return $this->feature_meta . ', ' . $this->optional_meta ;
		//@TODO: Mix `feature_meta` and `optional_meta`

	}

	public function getOptionalMetaArrayAttribute()
	{
		$string = str_replace(' ' , null , $this->spreadMeta()->optional_meta) ;
		$result = [] ;

		$array = explode(',',$string);
		foreach($array as $item) {
			if(str_contains($item , '*')) {
				$required = true ;
				$item = str_replace('*' , null , $item) ;
			}
			else
				$required = false ;

			$field = explode(':' , $item) ;
			if(!$field[0])
				continue ;

			array_push($result , [
				'name' => $field[0],
				'type' => isset($field[1])? $field[1] : 'text' ,
				'required' => $required ,
			]);
		}

		return $result ;
	}


	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/
	public function hasFeature($feature)
	{
		return str_contains($this->features , $feature);
	}

	public function has($feature)
	{
		return $this->hasFeature($feature);
	}

	public function hasnot($feature)
	{
		return !$this->has($feature);
	}

	public function hasAnyOf($features)
	{
		foreach($features as $feature) {
			if($this->has($feature))
				return true ;
		}

		return false ;
	}

	public function hasAllOf($features)
	{
		foreach($features as $feature) {
			if($this->hasnot($feature))
				return false ;
		}

		return true ;
	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public function templatesCombo()
	{
		$array = [] ;
		foreach(self::$available_templates as $template) {
			array_push($array , [
				$template ,
				trans("posts.templates.$template")
			]);
		}

		return $array ;
	}

}
