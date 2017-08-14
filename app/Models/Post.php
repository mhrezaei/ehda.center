<?php

namespace App\Models;

use App\Traits\EhdaPostTrait;
use App\Providers\UploadServiceProvider;
use App\Traits\PostFeedTrait;
use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Feed\FeedItem;


class Post extends Model implements FeedItem
{
	use TahaModelTrait, SoftDeletes;
	use EhdaPostTrait ;
	use PostFeedTrait ;

	public static    $reserved_slugs  = "none,without";
	public static    $meta_fields     = ['dynamic'];
	protected static $search_fields   = ['title', 'slug']; //to be used in Requests
	protected        $guarded         = [];
	protected        $casts           = [
		'is_draft'     => "boolean",
		'is_limited'   => "boolean",
		'published_at' => 'datetime',
		'meta'         => "array",
	];
	private          $cached_posttype = false;
	private          $cached_parent   = false;

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

	public function goods($regardless_of_availability = false)
	{
		$table = Good::where('sisterhood', $this->sisterhood);

		if($regardless_of_availability) {
			//$table->withTrashed() ;
		}
		else {
			$table->where('locales', 'like', "%$this->locale%");
		}

		return $table;
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
	public function registers()
	{
		return User::where('from_event_id' , $this->id) ;
	}

	public function getRegistersAttribute()
	{
		return $this->registers()->get();
	}


	public function getSafeTitleAttribute()
	{
		if($this->has('long_title')) {
			$this->title = $this->long_title ;
		}

		return str_limit($this->title , 200) ;
	}


	public function getRequiredRolesArrayAttribute()
	{
		$domains_array = $this->domains_array ;
		if(!count($domains_array)) {
			return 'admin' ;
		}
		elseif( count($domains_array) == 1 and head($domains_array) == 'global') {
			return 'manager' ;
		}
		else {
			$result_array = [] ;
			foreach($domains_array as $key => $domain) {
				if($domain!='global') {
					$result_array[] = User::$role_prefix_for_domain_admins.'-'.$domain ;
				}
			}
			return $result_array ;
		}
	}


	public function getDomainsArrayAttribute()
	{
		if($this->has('domains')) {
			return array_unique(array_filter(explode('|' , $this->domains)));
		}
		else {
			return [] ;
		}
	}


	public function getDomainNameAttribute()
	{
		if(!$this->domains or $this->hasnot('domains')) {
			return false ;
		}
		$domain_value = str_replace('|', null, $this->domains);

		/*-----------------------------------------------
		| If simply 'global' ...
		*/
		if($domain_value == 'global') {
			return trans('posts.form.global');
		}

		/*-----------------------------------------------
		| Otherwise ...
		*/
		if(str_contains($domain_value, 'global')) {
			$extra  = " ( ".trans("posts.form.reflect_in_global_short")." ) ";
			$domain_value = str_replace('global', null, $domain_value);
		}
		else {
			$extra = null;
		}

		$domain = Domain::findBySlug($domain_value) ;
		if(!$domain) {
			return false ;
		}
		else {
			return $domain->title . $extra ;
		}

	}


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

	public function getDiscountAmountAttribute()
	{
		return max($this->price - $this->sale_price, 0);
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

	public function getBrowseLinkAttribute()
	{
		return "manage/posts/$this->type/all/id=$this->id";
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
		return url("manage/posts/" . $this->type . "/edit/" . $this->hash_id);
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
		$array = $this->spreadMeta()->post_photos;
		if(!$array) {
			return [];
		}
		else {
			return $array;
		}
	}

	public function getParentAttribute()
	{
		if($this->cached_parent) {
			$parent = $this->cached_parent;
		}
		else {
			if($this->isCopy()) {
				$parent = Self::find($this->copy_of);
				if(!$parent) {
					$parent = new Post();
				}
			}
			else {
				$parent = $this;
			}
		}

		return $parent;
	}

	public function getDirectUrlAttribute()
	{
		switch ($this->type) {
			//            case 'products':
			//                return url_locale('products' . DIRECTORY_SEPARATOR . 'pd-' . ($this->hash_id));
			//                break;
			//            case 'news':
			//                return url_locale('news' . DIRECTORY_SEPARATOR . 'nw-' . ($this->hash_id));
			//                break;
			//            case 'faqs':
			//                return url_locale('faqs' . DIRECTORY_SEPARATOR . 'faq-' . ($this->hash_id));
			//                break;
			//            case 'teammates':
			//                return url_locale('teammates' . DIRECTORY_SEPARATOR . 'tm-' . ($this->hash_id));
			//                break;
			default:
				return url_locale(implode(DIRECTORY_SEPARATOR, [
					'show-post',
					($this->hash_id),
					urlencode($this->title),
				]));
				break;
		}
	}
	public function getShortUrlAttribute()
	{
		return url_locale('-' . $this->hash_id);
	}



	public function getViewableFeaturedImageAttribute()
	{
		$this->spreadMeta();
		if($this->featured_image) {
			return url($this->featured_image);
		}
		else {
			if($typeImage = $this->posttype->spreadMeta()->default_featured_image) {
				return url($typeImage);
			}
		}

		return '' ;
	}

	public function getViewableFeaturedImageThumbnailAttribute()
	{
		$image = $this->viewable_featured_image;

		return UploadServiceProvider::getThumb($image);
	}

	public function getViewableAlbumAttribute()
	{
		$this->spreadMeta();
		$album = $this->post_photos;
		if($album and is_array($album) and count($album)) {
			foreach($album as &$image) {
				$image['src'] = url($image['src']);
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
				$image['src'] = str_replace_last('/', '/thumbs/', $image['src']);
			}

			return $album;
		}

		return [];
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
		/*-----------------------------------------------
		| Considering Languages ...
		*/
		$full_permit = "posts-$this->type.$permit" ;
		if($this->has('locales')) {
			$full_permit .= ".$this->locale" ;
		}

		/*-----------------------------------------------
		| Considering Domains ...
		*/
		$roles_array = $this->required_roles_array ;


		/*-----------------------------------------------
		| Return ...
		*/
		//ss($full_permit);
		//ss(user()->as('volunteer-kashan')->pivot('permissions'));
		return user()->as($roles_array)->can($full_permit) ;
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
			'domain'   => null,
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
		if(!is_array($switch['type']) and str_contains($switch['type'], 'feature:')) {
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
		| Process Domain ...
		*/
		if($switch['domain'] == 'auto') {
			if(user()->is_a('manager')) {
				$switch['domain'] = null;
			}
			else {
				$switch['domain'] = user()->domainsArray("posts-".$switch['type']);
			}
		}

		if($switch['domain']) {
			$switch['domain'] = (array)$switch['domain'];

			$table->where( function($query) use ($switch) {
				$query->where('id' , '0') ;

				foreach($switch['domain'] as $domain) {
					$query->orWhere('domains', 'like', "%|$domain|%");
				}

			});
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

		$slug = str_slug(str_limit($slug, 50));

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
		return Unit::all();
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

	public function similars($number = null)
	{
		$posts = Post::where([
			['id', '<>', $this->id],        // not self post
			'type' => $this->type,          // similar post type
		]);

		// similar categories
		$categories = $this->categories;
		if ($categories->count()) {
			$posts->whereHas('categories', function ($query) use ($categories) {
				$query->whereIn('categories.id', $categories->pluck('id')->toArray());
			});
		}

		// check availability for "products"
		if ($this->has('price')) {
			$posts->where(['is_available' => true]);
		}

		if ($number and is_int($number)) {
			// sort
			$posts->orderBy('published_at', 'DESC');

			return $posts->limit($number)->get();
		}

		return $posts;
	}


	public function canReceiveComments()
	{
		$this->spreadMeta();
		if (
			((user()->exists or $this->allow_anonymous_comment) and
				(!$this->disable_receiving_comments)) or
			setting()->ask('allow_anonymous_comment')->gain()
		) {
			return true;
		} else {
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
				if (!$this->isIt('AVAILABLE')) {
					break;
				}
				$freshTime = 100 * 24 * 60; // 100 days (in minutes) @TODO: should be saved in settings
				$publishTime = new Carbon($this->published_at);
				$now = Carbon::now();
				if ($now->gt($publishTime) and ($now->diffInMinutes($publishTime) <= $freshTime)) {
					return true;
				}
				break;

			case 'IN_SALE':
				if (!$this->isIt('AVAILABLE')) {
					break;
				}
				$this->spreadMeta();
				if ($this->sale_price and ($this->sale_price != $this->price)) {
					return true;
				}
				break;

			case 'AVAILABLE':
				if ($this->is_available) {
					return true;
				} else {
					return false;
				}
				break;
		}

		return false;
	}

	public function getAbstract()
	{
		$this->spreadMeta();
		if($this->abstract) {
			return $this->abstract;
		}

		return str_limit($this->text, 200);
	}


}

