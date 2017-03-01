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
		if($this->sisterhood > 0) {
			return self::where('sisterhood' , $this->sisterhood) ;
		}
		else {
			return self::where('id' , '0');
		}
	}

	public function copies()
	{
		return self::where('copy_of' , $this->id) ;
	}

	public function original()
	{
		if($this->copy_of == 0) {
			return new self();
		}
		else {
			$original = self::find($this->copy_of) ;
			if($original)
				return $original ;
			else
				return new self();
		}
	}

	public function in($locale)
	{
		if($locale==$this->locale)
			return $this ;

		$model = $this->sisters()->where('locale' , $locale)->first() ;
		if($model)
			return $model ;
		else {
			$model = new self() ;
			$model->locale = $locale ;
			$model->type = $this->type ;
			return $model ;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/

	public function getDiscountPercentAttribute()
	{
		if(!$this->price or $this->price == $this->sale_price)
			return null ;

		return round((($this->price - $this->sale_price) * 100) / $this->price) ;
	}


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
		if(!$this->exists)
			return 'unsaved' ;

		if($this->trashed())
			return 'deleted' ;

		if($this->isPublished())
			return 'published' ;

		if($this->isScheduled())
			return 'scheduled' ;

		if($this->isDraft())
			return 'draft';

		if($this->isPending())
			return 'pending' ;

		return 'unknown' ;

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


	public function getOtherLocalesAttribute()
	{
		$array = $this->posttype->locales_array ;
		$key = array_search($this->locale , $array) ;
		array_forget($array , $key);

		return $array ;
	}

	public function getCreateLinkAttribute()
	{
		return url("manage/posts/".$this->type."/create/".$this->locale) ;
	}

	public function getEditLinkAttribute()
	{
		return url("manage/posts/".$this->type."/edit/".$this->id) ;
	}


	public function getEditorMoodAttribute()
	{
		if(!$this->exists) {
			$mood = 'new' ;
		}
		elseif($this->copy_of) {
			$mood = 'copy' ;
		}
		else{
			$mood = 'original';
		}

		return $mood ;

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

	public function hasAnyOf($features)
	{
		return $this->posttype->hasAnyOf($features);
	}

	public function hasAllOf($features)
	{
		return $this->posttype->hasAllOf($features);
	}

	public function can($permit)
	{
		return user()->as('admin')->can('post-' . $this->type . '.' . $permit) ;
	}

	public function canPublish()
	{
		return $this->can('publish');
	}

	public function canEdit()
	{
		if(!$this->exists)
			return false ;

		if($this->isOwner() and !$this->isApproved() and $this->can('create'))
			return true ;

		if(!$this->isApproved() and $this->can('publish') and $this->can('edit'))
			return true ;

		return $this->can('edit');
	}

	public function canDelete()
	{
		if(!$this->exists)
			return true ;

		if($this->isOwner() and !$this->isApproved() and $this->can('create'))
			return true ;

		return $this->can('delete');
	}

	public function canBin()
	{
		if(!$this->exists)
			return false ;

		if($this->isOwner() and !$this->isApproved() and $this->can('create'))
			return true ;

		return $this->can('bin');
	}

	public function isPublished()
	{
		return ($this->published_by and $this->published_at and $this->published_at <= Carbon::now()) ;

	}

	public function isScheduled()
	{
		return ($this->published_by and $this->published_at and $this->published_at > Carbon::now()) ;
	}

	public function isApproved()
	{
		return boolval($this->published_by) ;
	}


	public function isDraft()
	{
		return $this->is_draft ;
	}

	public function isPending()
	{
		return (!$this->isDraft() and !$this->published_by) ;
	}

	public function isCopy()
	{
		return boolval($this->copy_of);
	}

	public function isOwner()
	{
		if(!$this->exists)
			return true ;

		return user()->id == $this->owned_by ;
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
	public function packageCombo()
	{
		return Package::all();
	}

	public function visibilityCombo()
	{
		return [
			['public' , trans('posts.visibility.public')],
			['limited' , trans('posts.visibility.limited')],
		];
	}

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

