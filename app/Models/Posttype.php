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
			'title' => ['header' , 'success' , 'true'],
			'text' => ['list' , 'success' , 'true'],
			'image' => ['file-image-o' , 'info' , true],
			'rss' => ['rss' , 'info' , 'true'],
			'comment' => ['comments-o' , 'info' , 'true'],
			'rate' => ['star-half-o' , 'info' , 'true' ] ,
			'album' => ['address-book-o' , 'info' , 'true'],
			'category' => ['tasks' , 'info' , 'true'],
			'searchable' => ['search' , 'info' , 'true'],
			'preview' => ['eye' , 'info' , 'true'],
			'digest' => ['fire' , 'info' , 'true'],
			'schedule' => ['clock-o' , 'info' , 'true'],
			'keywords' => ['tags' , 'info' , 'true'],
			'register' => ['user-plus' , 'warning' , 'true'],
			'visibility_choice' => ['shield' , 'warning' , 'true'],
			'template_choice' => ['th-large' , 'warning' , 'true'],
			'developers_only' => ['github-alt' , 'danger' , 'true'],
	];
	public static $available_templates = ['album' , 'post' , 'slideshow' , 'dialogue' , 'faq'] ;
	public static $available_meta_types = ['text' , 'textarea' , 'date' , 'boolean' , 'photo' , 'file'];
	public static $reserved_slugs = 'root,admin' ;
	public static $meta_fields = ['features' , 'template' , 'meta_fields' , 'visibility', 'singular_title' , 'icon'];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function posts()
	{
		return $this->hasMany('App\Models\Post');
	}


	public function categories()
	{
		return $this->hasMany('App\Models\Category');
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
		return self::$available_features ;
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
