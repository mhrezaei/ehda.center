<?php

namespace App\Models;

use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Vinkla\Hashids\Facades\Hashids;


class Post extends Model
{
	use TahaModelTrait, SoftDeletes;

	public static    $reserved_slugs  = "none,without";
	public static    $meta_fields     = ['dynamic'];
	protected static $search_fields   = ['title', 'slug']; //to be used in Requests
	protected        $guarded         = ['id'];
	protected        $casts           = [
		'is_draft'     => "boolean",
		'is_limited'   => "boolean",
		'published_at' => 'datetime',
	];
	private          $cached_posttype = false;

	/*
	|--------------------------------------------------------------------------
	| Relations
	|--------------------------------------------------------------------------
	|
	*/

	public function categories()
	{
		return $this->belongsToMany('App\Models\Category')->withTimestamps();
	}

	public function folders()
	{
		return $this->belongsToMany('App\Models\Folder')->withTimestamps();
	}

	public function roles()
	{
		return $this->belongsToMany('App\Models\Role')->withTimestamps(); //@TODO: complete with withPivot('permissions' , 'deleted_at') perhaps
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

	public function comments()
	{
		return $this->hasMany('App\Models\Comment');
	}

	public function sisters()
	{
		return self::where('sisterhood', $this->sisterhood);
	}

	public function copies()
	{
		return self::where('copy_of', $this->id);
	}

	public function original()
	{
		if($this->copy_of == 0) {
			return new self();
		}
		else {
			$original = self::find($this->copy_of);
			if($original) {
				return $original;
			}
			else {
				return new self();
			}
		}
	}

	public function in($locale)
	{
		if($locale == $this->locale) {
			return $this;
		}

		$model = $this->sisters()->where('locale', $locale)->first();
		if($model) {
			return $model;
		}
		else {
			$model             = new self();
			$model->locale     = $locale;
			$model->type       = $this->type;
			$model->sisterhood = $this->sisterhood;

			return $model;
		}
	}

	public function getReceiptsAttribute()
	{
		//$this->spreadMeta() ;
		if($this->hasnot('event')) {
			return Receipt::where('id', '0');
		}
		else {
			return Receipt::whereBetween('purchased_at', [$this->starts_at, $this->ends_at]);
		}
	}


	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	|
	*/
	public function cacheUpdate()
	{
		$this->cacheUpdateReceipts();
		$this->cacheUpdateComments();
	}

	public function cacheRegenerateOnUpdate()
	{
		session()->put('test', 'triggered1');
		if($this->has('event')) {
			$this->cacheUpdateReceipts();
			session()->put('test', 'triggered2');
		}
	}

	public function cacheUpdateReceipts()
	{
		$this->updateMeta([
			'total_receipts_count'  => $this->receipts->count(),
			'total_receipts_amount' => $this->receipts->sum('purchased_amount'),
		], true);
	}

	public function cacheUpdateComments()
	{
		$this->updateMeta([
			'total_comments' => $this->comments()->count(),
		], true);
	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/

	/**
	 * @return array ;
	 * used only on the posts with drawing capabilities
	 */
	public function getWinnersArrayAttribute()
	{
		$winners = $this->meta('winners');
		if(!is_array($winners)) {
			$winners = [];
		}

		return $winners;
	}


	public function getImageAttribute()
	{
		$image_url = $this->spreadMeta()->featured_image;
		if(!$image_url) {
			$image_url = 'assets/images/close.png';
		}

		return asset($image_url);
	}


	public function getDiscountPercentAttribute()
	{
		if(!$this->price or $this->price == $this->sale_price) {
			return null;
		}

		return round((($this->price - $this->sale_price) * 100) / $this->price);
	}


	public function getIddAttribute()
	{
		return Crypt::encrypt($this->id);
	}

	public function getEncryptedTypeAttribute()
	{
		return $this->posttype->encrypted_slug;
	}


	public function getStatusAttribute()
	{
		if(!$this->exists) {
			return 'unsaved';
		}

		if($this->trashed()) {
			return 'deleted';
		}

		if($this->isPublished()) {
			return 'published';
		}

		if($this->isScheduled()) {
			return 'scheduled';
		}

		if($this->isDraft()) {
			return 'draft';
		}

		if($this->isPending()) {
			return 'pending';
		}

		return 'unknown';

	}

	public function getSiteLinkAttribute()
	{
		return $this->locale . '/page/' . $this->id;
	}

	public function getPreviewLinkAttribute()
	{
		//@TODO: Preview Link
	}


	public function getOtherLocalesAttribute()
	{
		$array = $this->posttype->locales_array;
		$key   = array_search($this->locale, $array);
		array_forget($array, $key);

		return $array;
	}

	public function getCreateLinkAttribute()
	{
		return url("manage/posts/" . $this->type . "/create/" . $this->locale . "/" . $this->sisterhood);
	}

	public function getEditLinkAttribute()
	{
		return url("manage/posts/" . $this->type . "/edit/" . $this->id);
	}


	public function getEditorMoodAttribute()
	{
		if(!$this->exists) {
			$mood = 'new';
		}
		elseif($this->copy_of) {
			$mood = 'copy';
		}
		else {
			$mood = 'original';
		}

		return $mood;

	}

	public function getNormalizedSlugAttribute()
	{
		return self::normalizeSlug($this->id, $this->type, $this->locale, $this->slug);
	}

	public function getCategoryIdsAttribute()
	{
		$categories = $this->categories;
		$list       = [];
		foreach($categories as $category) {
			array_push($list, $category->id);
		}

		return $list;
	}

	public function getPhotosAttribute()
	{
		$array = $this->meta('post_photos');
		if(!$array) {
			return [];
		}
		else {
			return $array;
		}
	}


	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/

	public function isUnder($category)
	{
		return (in_array($category->id, $this->category_ids));
	}

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
		return user()->as('admin')->can('post-' . $this->type . '.' . $permit);
	}

	public function canPublish()
	{
		return !$this->trashed() and $this->can('publish');
	}

	public function canEdit()
	{
		if(!$this->exists or $this->trashed()) {
			return false;
		}

		if($this->isOwner() and !$this->isApproved() and $this->can('create')) {
			return true;
		}

		if(!$this->isApproved() and $this->can('publish') and $this->can('edit')) {
			return true;
		}

		return $this->can('edit');
	}

	public function canDelete()
	{
		if(!$this->exists) {
			return true;
		}

		if($this->isOwner() and !$this->isApproved() and $this->can('create')) {
			return true;
		}

		return $this->can('delete');
	}

	public function canBin()
	{
		if(!$this->exists) {
			return false;
		}

		if($this->isOwner() and !$this->isApproved() and $this->can('create')) {
			return true;
		}

		return $this->can('bin');
	}

	public function isPublished()
	{

		return (!$this->trashed() and $this->published_by and $this->published_at and $this->published_at <= Carbon::now());

	}

	public function isScheduled()
	{
		return ($this->published_by and $this->published_at and $this->published_at > Carbon::now());
	}

	public function isApproved()
	{
		return boolval($this->published_by);
	}

	public function isRejected()
	{
		return boolval($this->moderated_by and !$this->published_by);
	}


	public function isDraft()
	{
		return $this->is_draft;
	}

	public function isPending()
	{
		return (!$this->isDraft() and !$this->published_by);
	}

	public function isCopy()
	{
		return boolval($this->copy_of);
	}

	public function isOwner()
	{
		if(!$this->exists) {
			return true;
		}

		return user()->id == $this->owned_by;
	}


	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/
	public static function wherePublished()
	{
		return self::where('published_by', '>', '0');
	}

	public static function ownedBy($user_id)
	{
		return self::where('owned_by', $user_id);
	}

	public static function selector($parameters = [])
	{
		$switch = array_normalize($parameters, [
			'id'       => "0",
			'slug'     => "",
			'role'     => "user", //@TODO
			'criteria' => "published",
			'locale'   => getLocale(),
			'owner'    => 0,
			'type'     => "feature:searchable",
			'category' => '', //supports single or an array of id,slug, or both
			'keyword'  => "", //[@TODO
			'search'   => "",
			'from'     => null,
			'to'       => null,
			'folder'   => "",   //supports single or an array of id,slug, or both
		]);

		$table = self::where('id', '>', '0');

		/*-----------------------------------------------
		| Simple Things...
		*/
		if($switch['slug']) {
			$table = $table->where('slug', $switch['slug']);
		}
		if($switch['id']) {
			$table = $table->where('id', $switch['id']);
		}
		if($switch['from']) {
			$table = $table->whereDate('starts_at', '<=', $switch['from']);
		}
		if($switch['to']) {
			$table = $table->whereDate('ends_at', '>=', $switch['to']);
		}

		/*-----------------------------------------------
		| Category ...
		*/
		if($switch['category']) {
			if($switch['category'] == 'no') {
				$table = $table->has('categories', '=', 0);
			}
			elseif(!is_array($switch['category'])) {
				$switch['category'] = [$switch['category']];
			}

			if(is_array($switch['category']) and count($switch['category'])) {
				$table = $table->whereHas('categories', function ($query) use ($switch) {
					$query->whereIn('categories.id', $switch['category'])->orWhereIn('categories.slug', $switch['category']);
				});
			}
		}

		/*-----------------------------------------------
		| Folder ...
		*/
		if($switch['folder']) {
			if($switch['folder'] == 'no') {
				$table = $table->has('folders', '=', 0);
			}
			elseif(!is_array($switch['folder'])) {
				$switch['folder'] = [$switch['folder']];
			}

			if(is_array($switch['folder']) and count($switch['folder'])) {
				$table = $table->whereHas('folders', function ($query) use ($switch) {
					$query->whereIn('folders.id', $switch['folder'])->orWhereIn('folders.slug', $switch['folder']);
				});
			}
		}

		/*-----------------------------------------------
		| Process Type ...
		*/
		if(str_contains($switch['type'], 'feature:')) {
			$feature        = str_replace('feature:', null, $switch['type']);
			$switch['type'] = Posttype::withFeature($feature); //returns an array of posttypes
		}

		//when an array of selected posttypes are requested
		if(is_array($switch['type'])) {
			$table = $table->whereIn('type', $switch['type']);
		}

		//when 'all' posttypes are requested
		elseif($switch['type'] == 'all') {
			// nothing required here :)
		}

		//when an specific type is requested
		else {
			$table = $table->where('type', $switch['type']);
		}

		/*-----------------------------------------------
		| Process Locale ...
		*/
		if(in_array($switch['locale'], ['all', null])) {
			//nothing to do :)
		}
		else {
			$table = $table->where('locale', $switch['locale']);
		}

		/*-----------------------------------------------
		| Process Owner ...
		*/
		if($switch['owner'] > 0) {
			$table = $table->where('owned_by', $switch['owner']);
		}

		/*-----------------------------------------------
		| Process Criteria ...
		*/
		$now = Carbon::now()->toDateTimeString();
		switch ($switch['criteria']) {
			case 'all' :
				break;

			case 'all_with_trashed' :
				$table = $table->withTrashed();
				break;

			case 'published':
				$table = $table->whereDate('published_at', '<=', $now)->where('published_by', '>', '0');
				break;

			case 'scheduled' :
				$table = $table->whereDate('published_at', '>', $now)->where('published_by', '>', '0');
				break;

			case 'pending':
				$table = $table->where('published_by', '0')->where('is_draft', 0);
				break;

			case 'drafts' :
				$table = $table->where('is_draft', 1)->where('published_by', '0');
				break;

			case 'rejected' :
				$table = $table->where('moderated_by', '>', '0')->where('published_by', '0');
				break;

			case 'my_posts' :
				$table = $table->where('owned_by', user()->id);
				break;

			case 'my_drafts' :
				$table = $table->where('owned_by', user()->id)->where('is_draft', true)->where('published_by', '0');
				break;

			case 'bin' :
				$table = $table->onlyTrashed();
				break;

			default:
				$table = $table->where('id', '0');
				break;

		}


		/*-----------------------------------------------
		| Process Search ...
		*/
		if($switch['search']) {
			$table = $table->whereRaw(self::searchRawQuery($switch['search']));
		}


		//Return...
		return $table;

	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/

	public static function savePhotos($data)
	{
		$resultant_array = [];
		unset($data['_photo_src_NEW']);

		foreach($data as $field => $value) {
			if(str_contains($field, '_photo_src_')) {
				$label_field = str_replace('src', 'label', $field);
				$link_field  = str_replace('src', 'link', $field);
				array_push($resultant_array, [
					'src'   => str_replace(url('') . '/', null, $value),
					'label' => $data[ $label_field ],
					'link'  => $data[ $link_field ],
				]);
			}
		}

		return $resultant_array;
	}

	public function saveCategories($data)
	{
		$selected_categories = [];
		$selected_folders    = [];
		foreach($data as $key => $value) {
			if(str_contains($key, 'category') and $value) {
				$category_id = Category::realId(str_replace('category-', null, $key));
				$category    = Category::find($category_id);
				if($category) {
					array_push($selected_categories, $category->id);
					array_push($selected_folders, $category->folder_id);
				}
			}
		}

		$this->categories()->sync($selected_categories);
		$this->folders()->sync($selected_folders);


	}

	public static function normalizeSlug($post_id, $post_type, $post_locale, $slug)
	{
		//preparations...
		$found_a_unique_slug = false;
		$tries               = 1;

		//Invalid Patterns...
		if(!$slug) {
			return '';
		}

		$slug = str_slug(str_limit($slug, 30));

		//General Corrections...
		$slug = strtolower($slug);
		if(str_contains("01234567890-_", $slug[0])) {
			$slug = "p" . $slug;
		}

		$purified_original_slug = $slug;
		if(in_array($slug, explode(',', self::$reserved_slugs))) {
			$tries++;
			$slug .= "-" . strval($tries);
		}


		//loop...
		while (!$found_a_unique_slug) {
			$used = self::where('id', '!=', $post_id)->where('type', $post_type)->where('locale', $post_locale)->where('slug', $slug)->where('copy_of', 0)->withTrashed()->count();
			if($used) {
				$tries++;
				$slug = $purified_original_slug . "-" . strval($tries);
			}
			else {
				$found_a_unique_slug = true;
			}
		}

		//return...
		return $slug;

	}

	public function packageCombo()
	{
		return Package::all();
	}

	public function visibilityCombo()
	{
		return [
			['public', trans('posts.visibility.public')],
			['limited', trans('posts.visibility.limited')],
		];
	}

	public static function checkManagePermission($posttype, $criteria)
	{
		switch ($criteria) {
			case 'search' :
				$permit = '*';
				break;

			case 'pending':
			case 'drafts' :
				$permit = '*';
				break;

			case 'my_posts' :
			case 'my_drafts' :
				$permit = 'create';
				break;

			case 'bin' :
				$permit = '*';
				break;

			default :
				$permit = '*';
		}

		return user()->as('admin')->can("posts-$posttype.$permit");
	}

	public function prepareForDrawing()
	{
		return Drawing::prepareDatabase($this);
	}

	public function isDrawingReady()
	{
		return Drawing::isReady($this->id);
	}

	public function getViewableFeaturedImageAttribute()
	{
		$this->spreadMeta();
		if($this->featured_image and url_exists(url($this->featured_image))) {
			return url($this->featured_image);
		}
		else {
			if($typeImage = $this->posttype->spreadMeta()->default_featured_image) {
				return url($typeImage);
			}
		}
	}

	public function getViewableFeaturedImageThumbnailAttribute()
	{
		$image = $this->viewable_featured_image;

		return str_replace_last('/', '/thumbs/', $image);
	}

	public function getViewableAlbumAttribute()
	{
		$this->spreadMeta();
		$album = $this->album;
		if($album and is_array($album and count($album))) {
			foreach($album as &$image) {
				$image = url($image);
			}

			return $album;
		}

		return [];
	}

	public function getViewableAlbumThumbnailsAttribute()
	{
		$album = $this->viewable_album;
		if($album and is_array($album) and $album) {
			foreach($album as &$image) {
				$image = str_replace_last('/', '/thumbs/', $image);
			}

			return $album;
		}

		return [];
	}

	public function getDirectUrlAttribute()
	{
		switch ($this->type) {
			case 'products':
				return url_locale('products/pd-' . ($this->id));
				break;
			case 'news':
				return url_locale('news/nw-' . ($this->id));
				break;
			case 'faqs':
				return url_locale('faqs/faq-' . ($this->id));
				break;
		}
	}

	public function similars($number = null)
	{
		$posts = Post::where([
			['id', '<>', $this->id],        // not self post
			'type' => $this->type,          // similar post type
		]);

		// similar categories
		$categories = $this->categories;
		if($categories->count()) {
			$posts->whereHas('categories', function ($query) use ($categories) {
				$query->whereIn('categories.id', $categories->pluck('id')->toArray());
			});
		}

		// check availability for "products"
		if($this->has('price')) {
			$posts->where(['is_available' => true]);
		}

		if($number and is_int($number)) {
			// sort
			$posts->orderBy('published_at', 'DESC');

			return $posts->limit($number)->get();
		}

		return $posts;
	}

	public function getCurrentPriceAttribute()
	{
		if($this->isIt('IN_SALE')) {
			return $this->sale_price;
		}
		else {
			return $this->price;
		}
	}


	public function canRecieveComments()
	{
		$this->spreadMeta();
		if((user()->exists or $this->allow_anonymous_comment) and
			(!$this->disable_receiving_comments)
		) {
			return true;
		}
		else {
			return false;
		}
	}

	public function canShowComments()
	{
		$this->spreadMeta();
		if(!$this->disable_showing_comments) {
			return true;
		}
		else {
			return false;
		}
	}

	public function isIt($switch)
	{
		$switch = strtoupper($switch);
		switch ($switch) {
			case 'NEW':
				if(!$this->isIt('AVAILABLE')) {
					break;
				}
				$freshTime   = 100 * 24 * 60; // 100 days (in minutes) @TODO: should be saved in settings
				$publishTime = new Carbon($this->published_at);
				$now         = Carbon::now();
				if($now->gt($publishTime) and ($now->diffInMinutes($publishTime) <= $freshTime)) {
					return true;
				}
				break;

			case 'IN_SALE':
				if(!$this->isIt('AVAILABLE')) {
					break;
				}
				$this->spreadMeta();
				if($this->sale_price and ($this->sale_price != $this->price)) {
					return true;
				}
				break;

			case 'AVAILABLE':
				if($this->is_available) {
					return true;
				}
				else {
					return false;
				}
				break;
		}

		return false;
	}

}

