<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Good extends Model
{
	use TahaModelTrait, SoftDeletes;

	public static $meta_fields    = ['min_purchase', 'max_purchase', 'image'];

	protected $guarded         = ['id'];
	private   $cached_posttype = false;

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function posts($locale = null)
	{
		$table = Post::where('sisterhood' , $this->sisterhood) ;
		if($locale)
			$table->where('locale' , $locale) ;

		return $table ;
	}

	public function unit()
	{
		return $this->belongsTo('App\Models\Unit');
	}

	public function pack()
	{
		return $this->belongsTo('App\Models\Pack');
	}

	public function posttype()
	{
		$posttype = Posttype::findBySlug($this->type);

		if($posttype) {
			return $posttype;
		}
		else {
			return new Posttype();
		}
	}

	public function getPosttypeAttribute()
	{
		if(!$this->cached_posttype) {
			$this->cached_posttype = $this->posttype();
		}

		return $this->cached_posttype;
	}

	public function setPosttype($model)
	{
		$this->cached_posttype = $model;
	}

	/*
	|--------------------------------------------------------------------------
	| Assessors and Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getDiscountAmountAttribute()
	{
		return max( $this->price - $this->sale_price , 0);
	}

	public function getDiscountPercentAttribute()
	{
		if(!$this->price or $this->price == $this->sale_price) {
			return null;
		}

		return round((($this->price - $this->sale_price) * 100) / $this->price);
	}

	public function getEncryptedTypeAttribute()
	{
		return $this->posttype->encrypted_slug;
	}

	public function getHasDiscountAttribute()
	{
		return boolval($this->sale_price < $this->price) ;
	}


	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/
	public function titleIn($locale = 'fa')
	{
		if($locale == 'fa') {
			return $this->title;
		}
		else {
			return $this->spreadMeta()->locale_titles["title-$locale"] ;
		}
	}

	public function isAvailableIn($locale)
	{
		return boolval($this->exists and str_contains($this->locales , $locale)) ;
	}


	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public function colorsCombo()
	{
		$colors = getSetting('good_colors') ;
		$array = [] ;
		if(!$colors or !is_array($colors)) {
			return [];
		}

		foreach($colors as $color) {
			$array[] = [$color , trans("colors.$color")] ;
		}

		return $array ;
	}

}
