<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Post extends Model
{
	use TahaModelTrait , SoftDeletes ;

	protected $guarded= ['id'];
	protected static $search_fields = ['title' , 'keywords' , 'slug'] ;
	public static $reserved_slugs = "none,without"; //to be used in Requests

	protected $casts = [
		'is_draft' => "boolean",
		'is_limited' => "boolean",
		'published_at' => 'datetime' ,
	];

	public static $meta_fields = ['dynamic'] ;

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	| 
	*/
	public function categories()
	{
		return $this->belongsToMany('App\Models\Category')->withTimestamps();;
	}
	public function roles()
	{
		return $this->belongsToMany('App\Models\Role')->withTimestamps();; //@TODO: complete with withPivot('permissions' , 'deleted_at') perhaps
	}

	public function posttype()
	{
		return $this->belongsTo('App\Models\Posttype') ;
	}

	public function comments()
	{
		return $this->hasMany('App\Models\Comment');
	}

	public function sisters()
	{
		return self::where('parent_id' , $this->parent_id) ;
	}

	public function drafts()
	{
		return self::where('parent_id' , $this->parent_id)->where('is_draft' , 1);
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getIddAttribute()
	{
		return Crypt::encrypt($this->id);
	}

	public function getStatusAttribute()
	{

	}

	public function getCreatorAttribute()
	{
		$user = User::withTrashed()->find($this->created_by) ;
		if(user)
			return $user ;
		else
			return new User() ;
	}

	public function getPublisherAttribute()
	{
		$user = User::withTrashed()->find($this->published_by) ;
		if(user)
			return $user ;
		else
			return new User() ;
	}

	public function getSiteLinkAttribute()
	{

	}

	public function getPreviewLinkAttribute()
	{

	}





	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/
	public function canPublish()
	{
		
	}

	public function canEdit()
	{
		
	}

	public function canDelete()
	{
		
	}

	public function canBin()
	{
		
	}

	public function isPublished()
	{
		
	}

	public function isScheduled()
	{
		
	}


	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/
	public static function selector($parameters = [])
	{
		$parameters = array_normalize($parameters , [
			'role' => "user",
			'criteria' => "published",
			'lang' => getLocale(),
			'posttype' => "searchable",
			'category' => "",
		]);
	}
}

