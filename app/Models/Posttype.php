<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;


class Posttype extends Model
{
	use TahaModelTrait, SoftDeletes;
	public static $available_features   = [
		'text'              => ['list', 'success', []],
		'long_title'        => ['text-height', 'success', ['long_title:text']],
		'title2'            => ['subscript', 'success', ['title2:text']],
		'abstract'          => ['compress', 'success', ['abstract:text']],
		'featured_image'    => ['file-image-o', 'info', ['featured_image:photo']],
		'download'          => ['download', 'info', ['download_file:file']],
		'rss'               => ['rss', 'info', []],
		'comment'           => ['comments-o', 'info', ['allow_anonymous_comment:boolean', 'disable_receiving_comments:boolean', 'disable_showing_comments:boolean', 'comment_receive_day_limits:date']],
		'rate'              => ['star-half-o', 'info', []], //@TODO: feature_fields
		'album'             => ['address-book-o', 'info', ['post_photos:auto']],
		'domains'           => ['code-fork', 'info', []],
		'category'          => ['folder-o', 'info', []],
		'cat_image'         => ['file-image-o', 'info', []],
		'keywords'          => ['tags', 'info', []], //@TODO: feature_fields
		'listable'          => ['bars', 'info', []],
		'searchable'        => ['search', 'info', []],
		'preview'           => ['eye', 'info', []],
		'seo'               => ['stethoscope', 'info', ['seo_status:text']],
		'price'             => ['dollar', 'warning', ['sale_price:text', 'sale_expires_at:text', 'package_id:text']],
		'basket'            => ['shopping-basket', 'warning', []],
		'history_system'    => ['clock-o', 'success', []],
		'full_history'      => ['history', 'success', []],
		'digest'            => ['fire', 'info', []],
		'schedule'          => ['clock-o', 'info', ['original_published_at:auto']],
		'event'             => ['calendar', 'warning', []],
		'register'          => ['user-plus', 'warning', []],
		'visibility_choice' => ['shield', 'warning', []],
		//			'template_choice' => ['th-large' , 'warning' , []],
		//			'locales' => ["globe" , 'danger' , []] ,
		'slug'              => ['hashtag', 'danger', []],
		'developers_only'   => ['github-alt', 'danger', []],
	];
	public static $available_templates  = ['album', 'post', 'product', 'slideshow', 'dialogue', 'faq', 'special'];
	public static $available_meta_types = ['text', 'textarea', 'date', 'boolean', 'photo', 'file'];
	public static $reserved_slugs       = 'root,admin';
	public static $meta_fields          = [
		'features',
		'template',
		'feature_meta',
		'optional_meta',
		'visibility',
		'singular_title',
		'icon',
		'locales',
		'max_per_page',
		'default_featured_image',
		'featured_image_width',
		'featured_image_height',
		'fresh_time_duration',
		'locale_titles',
		'thumb_sizes',
		'gallery_thumb_size',
		'upload_configs',
	];
	public static $basement_meta        = "moderate_note:text ";
	public static $downstream           = [
		[
			'name'     => "max_per_page",
			'type'     => "text",
			'rules'    => "required|numeric|min:20|max:100",
			'purifier' => "ed",
		],
		[
			'name'        => "default_featured_image",
			'type'        => "photo",
			'rules'       => "sometimes|required",
			'in_features' => ['featured_image'],
		],
		[
			'name'        => "featured_image_width",
			'type'        => "text",
			'rules'       => "sometimes|required|numeric|min:20|max:500",
			'in_features' => ['featured_image'],
			'purifier'    => "ed",
		],
		[
			'name'        => "featured_image_height",
			'type'        => "text",
			'rules'       => "sometimes|required|numeric|min:20|max:500",
			'in_features' => ['featured_image'],
			'purifier'    => "ed",
		],
		[
			'name'     => "fresh_time_duration",
			'type'     => "text",
			'rules'    => "numeric|min:0|max:10",
			'purifier' => "ed",
		],
	];
	protected     $guarded              = ['id'];

	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/

	public static function withPermit($switches)
	{
		$switches = array_normalize($switches, [
			'role'    => "admin",
			'prefix'  => "posts",
			'permit'  => '*',
			'feature' => "",
		]);

		if($switches['feature']) {
			$feature = $switches['feature'];
			$types   = self::whereRaw("LOCATE('$feature' , `features`)")->get();
		}
		else {
			$types = self::all();
		}
		$result = [];
		foreach($types as $type) {
			if(user()->as($switches['role'])->can($switches['prefix'] . "-" . $type->slug . '.' . $switches['permit'])) {
				$result[] = $type->slug;
			}
		}

		return $result;
	}

	public static function withFeature($feature)
	{
		$models = self::whereRaw("LOCATE('$feature' , `features`)")->get();

		$result = [];
		foreach($models as $model) {
			array_push($result, $model->slug);
		}

		return $result;
	}

	public static function withoutFeature($feature)
	{
		$models = self::whereRaw("LOCATE('$feature' , `features`)")->get();

		$result = [];
		foreach($models as $model) {
			array_push($result, $model->slug);
		}

		return $result;

	}

	public static function groups()
	{
		return self::orderBy('header_title', 'desc')->groupBy('header_title');
	}

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/

	public function posts()
	{
		return Post::where('type', $this->slug);
		//		return $this->hasMany('App\Models\Post');
	}

	public function folders()
	{
		return $this->hasMany('App\Models\Folder');

	}

	public function comments()
	{
		return Comment::where('type', $this->slug);
	}

	public function goods()
	{
		return Good::where('type', $this->slug);
	}

	public function packs()
	{
		return Pack::where('type', $this->slug);
	}

	public function categories()
	{
		$foldersIds = $this->folders->pluck('id')->toArray();

		return Category::whereIn('id', $foldersIds)->get();
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
		return json_encode(self::$available_features);
	}

	public function getMetaFieldsAttribute()
	{
		$this->spreadMeta();

		return $this->feature_meta . ', ' . $this->optional_meta;
		//@TODO: Mix `feature_meta` and `optional_meta`

	}

	public function getOptionalMetaArrayAttribute()
	{
		$string = str_replace(' ', null, $this->spreadMeta()->optional_meta);
		$result = [];

		$array = explode(',', $string);
		foreach($array as $item) {
			if(str_contains($item, '*')) {
				$required = true;
				$item     = str_replace('*', null, $item);
			}
			else {
				$required = false;
			}

			$field = explode(':', $item);
			if(!$field[0]) {
				continue;
			}

			array_push($result, [
				'name'     => $field[0],
				'type'     => isset($field[1]) ? $field[1] : 'text',
				'required' => $required,
			]);
		}

		return $result;
	}

	public function getLocalesArrayAttribute()
	{
		$this->spreadMeta();
		if(!$this->locales) {
			return ['fa'];
		}
		else {
			$array = array_filter(explode(',', $this->locales));
			if(!sizeof($array)) {
				return ['fa'];
			}
			else {
				return $array;
			}
		}
	}

	public function getLocalesCountAttribute()
	{
		return count($this->locales_array);
	}


	public function getDefaultLocaleAttribute()
	{
		return $this->locales_array[0];
	}

	public function getThumbSizesArrayAttribute()
	{
		/*-----------------------------------------------
		| Spread Meta ...
		*/
		$meta_state = $this->meta_spread;
		$this->spreadMeta();

		/*-----------------------------------------------
		| Process ...
		*/
		$input         = strtolower($this->thumb_sizes);
		$array_layer_1 = array_filter(preg_split("/\\r\\n|\\r|\\n/", $input));
		$array_final   = [];

		foreach($array_layer_1 as $item) {
			$array_layer_2 = array_filter(explode('x', str_replace(' ', null, $item)));
			if(!isset($array_layer_2[0]) or !isset($array_layer_2[1]) or !is_numeric($array_layer_2[0]) or !is_numeric($array_layer_2[1])) {
				continue;
			}
			$array_final[] = $array_layer_2;
		}

		/*-----------------------------------------------
		| Suppress Meta ...
		*/
		if(!$meta_state) {
			$this->suppressMeta();
		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $array_final;

	}

	public function getMinThumbWidthAttribute()
	{
		return min(array_pluck($this->thumb_sizes_array, 0));
	}

	public function getMinThumbHeightAttribute()
	{
		return min(array_pluck($this->thumb_sizes_array, 1));
	}

	public function getGalleryThumbSizeArrayAttribute()
	{
		/*-----------------------------------------------
		| Spread Meta ...
		*/
		$meta_state = $this->meta_spread;
		$this->spreadMeta();

		/*-----------------------------------------------
		| Process ...
		*/
		$input = strtolower($this->gallery_thumb_size);

		$array_final = array_filter(explode('x', str_replace(' ', null, $input)));
		if(!isset($array_final[0]) or !isset($array_final[1]) or !is_numeric($array_final[0]) or !is_numeric($array_final[1])) {
			$array_final[] = [0, 0];
		}

		/*-----------------------------------------------
		| Suppress Meta ...
		*/
		if(!$meta_state) {
			$this->suppressMeta();
		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $array_final;


	}


	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/

	public function hasAnyOf($features)
	{
		foreach($features as $feature) {
			if($this->has($feature)) {
				return true;
			}
		}

		return false;
	}

	public function has($feature)
	{
		return $this->hasFeature($feature);
	}

	public function hasFeature($feature)
	{
		// Dynamic Features...
		switch ($feature) {
			case 'locales' :
				if(sizeof($this->locales_array) > 1) {
					return true;
				}
				else {
					return false;
				}

			case 'feedback' :
				return $this->hasAnyOf(['comment', 'rate', 'basket', 'register', 'event']);

		}

		// Normal Situations...
		return str_contains($this->features, $feature);
	}

	public function hasAllOf($features)
	{
		foreach($features as $feature) {
			if($this->hasnot($feature)) {
				return false;
			}
		}

		return true;
	}

	public function hasnot($feature)
	{
		return !$this->has($feature);
	}

	public function can($permit = '*', $as = 'admin')
	{
		return user()->as($as)->can("posts-" . $this->slug . '.' . $permit);
	}

	public function cannot($permit, $as = 'admin')
	{
		return !$this->can($permit, $as);
	}

	public function normalizeRequestLocale($request_locale)
	{
		if(!$request_locale or !in_array($request_locale, $this->locales_array)) {
			$locale = $this->locales_array[0];
		}
		else {
			return $request_locale;
		}
	}

	public function titleIn($locale = 'fa')
	{
		if($locale == 'fa') {
			return $this->title;
		}
		else {
			return $this->meta("locale_titles")["title-$locale"];
		}
	}

	/**
	 * Still Temporary
	 *
	 * @param string $locale
	 */
	public function headerTitleIn($locale = 'fa')
	{
		return $this->header_title;
	}

	public function singularTitleIn($locale)
	{
		if($locale == 'fa') {
			return $this->singular_title;
		}
		else {
			return $this->meta("locale_titles")["singular_title-$locale"];
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/

	public function templatesCombo()
	{
		$array = [];
		foreach(self::$available_templates as $template) {
			array_push($array, [
				$template,
				trans("posts.templates.$template"),
			]);
		}

		return $array;
	}

	public function localesCombo()
	{
		$array = [];
		foreach($this->locales_array as $locale) {
			array_push($array, [
				$locale,
				trans("forms.lang.$locale"),
			]);
		}

		return $array;
	}

	public function downstream()
	{
		$defined_settings   = self::$downstream;
		$available_settings = [];
		foreach($defined_settings as $setting) {
			if(!isset($setting['in_features']) or $this->hasAnyOf($setting['in_features'])) {
				$available_settings[] = collect($setting);
			}
		}

		return $available_settings;
	}

	/**
	 * Checks if files could be uploaded for this posttype
	 *
	 * @return mixed
	 */
	public function canUploadFile()
	{
		$this->spreadMeta();

		if($this->upload_configs and (is_array(json_decode($this->upload_configs, true)))) {
			return true;
		}

		return false;
	}

	public static function updateSlug($old_slug, $new_slug)
	{
		/*-----------------------------------------------
		| Bypass ...
		*/
		if($old_slug == $new_slug) {
			return true ;
		}

		/*-----------------------------------------------
		| Action ...
		*/
		$posts_updated = Post::where('type' , $old_slug)->update([
			'type' => $new_slug ,
		]);
		$types_updated = Comment::where('type' , $old_slug)->update([
			'type' => $new_slug ,
		]);
		$goods_updated = Good::where('type' , $old_slug)->update([
			'type' => $new_slug ,
		]);
		$packs_updated = Pack::where('type' , $old_slug)->update([
			'type' => $new_slug ,
		]);

		/*-----------------------------------------------
		| Return ...
		*/
		return $posts_updated and $types_updated and $goods_updated and $packs_updated ;


	}

}
