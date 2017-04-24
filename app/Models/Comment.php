<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
	use TahaModelTrait;

	public static $meta_fields = ['text'];
	protected     $guarded     = ['id'];
	protected     $casts       = [
		'meta'         => 'array',
		'published_at' => 'datetime',
	];

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/
	public function posts()
	{
		return $this->belongsTo('App\Models\Post');
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
		return $this->posttype();
	}

	public function children()
	{
		return self::where('replied_on', $this->id);
	}

	public function parent()
	{
		if($this->replied_on) {
			return self::find($this->replied_on);
		}
		else {
			return $this;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	|
	*/
	public function cacheRegenerateOnInsertOrDelete()
	{
		$this->post->cacheUpdateComments();
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/
	public function getStatusAttribute()
	{
		if($this->trashed()) {
			return 'deleted';
		}
		if($this->published_at) {
			return 'published';
		}
		else {
			return 'pending';
		}

	}

	public function getSiteLinkAttribute()
	{
		return url(); //@TODO: Complete This!
	}

	public function getPresentableAttribute()
	{
		return boolval($this->published_at) and !$this->is_private ;
	}

	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/
	public function can($permit = '*')
	{
		return user()->as('admin')->can('comment-' , $this->type . '.' . $permit) ;
	}

	public function canPublish()
	{
		return $this->can('publish') ;
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
		     'type' => "all" ,
		     'post_id' => "0" ,
		     'replied_on' => "0" ,
		     'email' => "" ,
		     'ip' => "" ,
		     'created_by' => "" ,
		     'published_by' => "" ,
		     'criteria' => "published" ,
		     'search' => "" ,
		]));

		$table = self::where('id' , '>' , '0') ;

		/*-----------------------------------------------
		| Process Type ...
		*/
		if(str_contains($type , 'feature:')) {
			$feature = str_replace('feature:' , null , $type) ;
			$type = Posttype::withFeature($feature); //returns an array of posttypes
		}

		//when an array of selected posttypes are requested
		if(is_array($type)) {
			$table = $table->whereIn('type' , $type) ;
		}

		//when 'all' posttypes are requested
		elseif($type=='all') {
			// nothing required here :)
		}

		//when an specific type is requested
		else {
			$table = $table->where('type' , $type);
		}

		/*-----------------------------------------------
		| Easy Switches ...
		*/
		if($post_id) {
			$table = $table->where('post_id' , $post_id) ;
		}
		if($replied_on) {
			$table = $table->where('replied_on' , $replied_on) ;
		}
		if($email) {
			$table = $table->where('email' , $email) ;
		}
		if($ip) {
			$table = $table->where('ip' , $ip) ;
		}
		if($created_by) {
			$table = $table->where('created_by' , $created_by) ;
		}
		if($published_by) {
			$table = $table->where('published_by' , $published_by) ;
		}

		/*-----------------------------------------------
		| Process Criteria ...
		*/
		switch($criteria) {
			case 'all' :
				break;

			case 'all_with_trashed' :
				$table = $table->withTrashed() ;
				break;

			case 'published' :
				$table = $table->whereNotNull('published_at') ;
				break ;

			case 'presentable' :
				$table = $table->whereNotNull('published_at')->where('is_private' , 0) ;
				break ;

			case 'pending':
				$table = $table->whereNull('published_at') ;
				break ;

			case 'bin' :
				$table = $table->onlyTrashed();
				break;

			default:
				$table = $table->where('id' , '0') ;
				break;

		}

		/*-----------------------------------------------
		| Process Search ...
		*/
		$table = $table->whereRaw(self::searchRawQuery($search));


		//Return...
		return $table ;



	}
}
