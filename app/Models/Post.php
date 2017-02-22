<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Carbon\Carbon;
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
		$posttype = Posttype::findBySlug($this->type);

		if($posttype)
			return $posttype ;
		else
			return new Posttype() ;
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

	public function getPosttypeAttribute()
	{
		return $this->posttype() ;
	}

	public function getIddAttribute()
	{
		return Crypt::encrypt($this->id);
	}

	public function getEncryptedTypeAttribute()
	{
		return $this->posttype->encrypted_slug ;
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
	public function has($feature)
	{
		return $this->posttype->has($feature);
	}

	public function hasnot($feature)
	{
		return !$this->has($feature);
	}

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
		extract(array_normalize($parameters , [
			'role' => "user",
			'criteria' => "published",
			'locale' => getLocale(),
			'type' => "feature:searchable",
			'category' => "",
			'keyword' => "",
		]));

		$table = self::where('id' , '>' , '0') ;

		//Process Type...
		if(str_contains($type , 'feature:')) {
			$feature = str_replace('feature:' , null , $type) ;
			$type = Posttype::withFeature($feature);
		}
		if(is_array($type)) {
			$table = $table->whereIn('type' , $type) ;
		}
		elseif($type=='all') {
			// nothing required here :)
		}
		else {
			$table = $table->where('type' , $type);
		}

		//Process Language...
		if($locale!='all') {
			$table = $table->where('locale' , $locale);
		}

		//Process Criteria...
		$now = Carbon::now()->toDateTimeString();
		switch($criteria) {
			case 'all' :
				break;

			case 'all_with_trashed' :
				$table = $table->withTrashed() ;
				break;

			case 'published':
				$table = $table->whereDate('published_at','<=',$now)->whereNotNull('published_by') ;
				break;

			case 'scheduled' :
				$table = $table->whereDate('published_at','>',$now)->whereNotNull('published_by') ;
				break;

			case 'pending':
				$table = $table->whereNull('published_by')->where('is_draft',false) ;
				break;

			case 'drafts' :
				$table = $table->where('is_draft',1)->whereNull('published_by');
				break;

			case 'my_posts' :
				$table = $table->where('created_by',user()->id);
				break ;

			case 'my_drafts' :
				$table = $table->where('created_by',user()->id)->where('is_draft',true)->whereNull('published_by');
				break;

			case 'bin' :
				$table = $table->onlyTrashed();
				break;

			default:
				$table = $table->where('id' , '0') ;
				break;

		}

		//Return...
		return $table ;

	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public static function checkManagePermission($posttype, $criteria)
	{
		switch($criteria) {
			case 'search' :
				$permit = 'search' ;
				break;

			case 'pending':
			case 'drafts' :
				$permit = 'publish' ;
				break;

			case 'my_posts' :
			case 'my_drafts' :
				$permit = 'create' ;
				break;

			case 'bin' :
				$permit = 'bin' ;
				break;

			default :
				$permit = '*' ;
		}

		return user()->as('admin')->can("post-$posttype.$permit");
	}
}

