<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Category extends Model
{
	use TahaModelTrait ;//, SoftDeletes ;

	protected $guarded = ['id'] ;
	public static $reserved_slugs = 'root,admin' ;
	public static $meta_fields = ['image'];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function folder()
	{
		return $this->belongsTo('App\Models\Folder');
	}

	public function posts()
	{
		return $this->belongsToMany('App\Models\Post')->withTimestamps();
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/
//	public function getHashIdAttribute()
//	{
//		return Hashids::encode($this->id);
//	}

	public function getDirectUrlAttribute()
	{
		return route('post.archive', [
		    'lang' => getLocale(),
		    'postType' => $this->folder->posttype->slug,
            'category' => $this->slug,
        ]);
	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public static function realId($hash_id)
	{
		return Hashids::decode($hash_id)[0];
	}

	public function foldersCombo()
	{
		$folders = Folder::where('posttype_id' , $this->folder->posttype_id)->where('locale' , $this->folder->locale)->orderBy('title')->get() ;
		return $folders ;
	}


}
