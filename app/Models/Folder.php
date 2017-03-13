<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Folder extends Model
{
	use TahaModelTrait;

	public static $reserved_slugs = 'root,admin,no';
	public static $meta_fields    = ['image'];
	protected     $guarded        = ['id'];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/

	public function posttype()
	{
		return $this->belongsTo('App\Models\Posttype');
	}

	public function categories()
	{
		return $this->hasMany('App\Models\Category');
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getDefaultFolderAttribute()
	{
		return self::firstOrCreate([
			'posttype_id' => $this->posttype_id,
			'locale'      => $this->locale,
			'slug'        => "no",
		]);

	}


	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/

	public static function updateDefaultFolders()
	{
		$posttypes = Posttype::whereRaw("LOCATE('category' , `features`)")->get();
		foreach($posttypes as $posttype) {
			$locales = $posttype->locales_array;
			foreach($locales as $locale) {
				self::firstOrCreate([
					'posttype_id' => $posttype->id,
					'locale'      => "$locale",
					'slug'        => "no",
				]);
			}
		}
	}

	public static function safeDestroy($folder_id)
	{
		$folder = self::find($folder_id);
		if(!$folder) {
			return true;
		}

		$default_folder = $folder->default_folder ;
		$folder->categories()->update([
			'folder_id' => $default_folder->id,
		]);

		$done = $folder->forceDelete() ;
		return boolval($done);

	}

}
