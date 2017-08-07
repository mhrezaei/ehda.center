<?php

namespace App\Models;

use App\Http\Controllers\Auth\LoginController;
use App\Traits\EhdaUserTrait;
use App\Traits\PermitsTrait2;
use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Cache;


class User extends Authenticatable
{
	use Notifiable, TahaModelTrait, SoftDeletes, PermitsTrait2;
	use EhdaUserTrait;

	public static $meta_fields     = [
		'preferences',
		'name_father',
		'home_tel',
		'postal_code',
		'address',
		'reset_token',
		'reset_token_expire',
		//'key',
		//'default_role_deleted_at',
	];
	public static $search_fields   = ['name_first', 'name_last', 'name_firm', 'code_melli', 'email', 'mobile','card_no'];
	public static $required_fields = ['name_first', 'name_last', 'code_melli', 'mobile', 'home_tel', 'birth_date', 'gender', 'marital'];
	protected     $guarded         = ['status'];
	protected     $hidden          = ['password', 'remember_token'];
	protected     $casts           = [
		'meta'                  => 'array',
		'newsletter'            => 'boolean',
		'password_force_change' => 'boolean',
		'published_at'          => 'datetime',
		'marriage_date'         => 'datetime',
		'birth_date'            => 'datetime',
		//'default_role_deleted_at'            => 'datetime',
	];


	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/

	public function receipts()
	{
		return $this->hasMany('App\Models\Receipt');

		//return Receipt::where('user_id', $this->id)->get(); //wrong
	}

	public function comments()
	{
		return $this->hasMany('App\Models\Comment');
	}

	public function posts()
	{
		return Post::where('created_by' , $this->id)->orWhere('owned_by' , $this->id)->orWhere('moderated_by' , $this->id)->orWhere('published_by' , $this->id);
	}

	public function getPostsAttribute()
	{
		return $this->posts->get() ;
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
	}

	public function cacheUpdateReceipts()
	{
		$this->total_receipts_count  = $this->receipts()->count();
		$this->total_receipts_amount = $this->receipts()->sum('purchased_amount');
		$this->update();
	}

	public function cacheRegenerateOnUpdate()
	{
		Cache::forget("user-$this->id");
	}

	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/
	public static function finder($username, $as_role = false, $username_field = 'auto')
	{
		if($username_field == 'auto') {
			$login_controller = new LoginController();
			$username_field   = $login_controller->username();
		}
		if($as_role == 'admin') {
			$as_role = Role::adminRoles();
		}

		$user = self::selector([
			$username_field => $username,
			'role'          => $as_role,
			'banned'        => false,
		])->orderBy('created_at', 'desc')->first()
		;

		if(!$user) {
			$user = new self();
		}

		return $user;
	}

	public static function selector($parameters = [])
	{
		$switch = array_normalize($parameters, [
			'id'         => false,
			'email'      => false,
			'code_melli' => false,
			'mobile'     => false,
			'roleString' => false , // <~~ Supports Arrays, with this pattern: [roleSlug.status] for active roles and [roleSlug-status] for disabled roles.
			'role'       => false, // <~~ Supports Arrays
			'status'     => false, // <~~ best works where only one role is given.
			'min_status' => false, // <~~ best works where only one role is given.
			'max_status' => false, // <~~ best works where only one role is given.
			'permits'    => false,  // <~~ Supports Arrays
			'search'     => false,
			'criteria'   => false,
			'banned'     => false,
			'bin'        => false,
			'domain'     => 'all',
		]);

		$table = self::where('id', '>', '0');

		/*-----------------------------------------------
		| Simple Things ...
		*/
		if($switch['id']) {
			$table->where('id', $switch['id']);
		}
		if($switch['email']) {
			$table->where('email', $switch['email']);
		}
		if($switch['code_melli']) {
			$table->where('code_melli', $switch['code_melli']);
		}
		if($switch['mobile']) {
			$table->where('mobile', $switch['mobile']);
		}

		/*-----------------------------------------------
		| Special commands inside status ...
		*/
		if($switch['status'] == 'bin') {
			if($switch['role'] == 'all' or str_contains($switch['roleString'] , 'all.')) {
				$switch['bin'] = true;
				$switch['status'] = false ;
			}
			else {
				$switch['banned'] = true;
				$switch['status'] = false ;
			}
		}
		elseif($switch['status'] == 'all') {
			$switch['status'] = false ;
		}

		/*-----------------------------------------------
		| RoleStatus ...
		*/
		if($switch['roleString'] !== false and !str_contains($switch['roleString'] , 'all.')) {

			if(!is_array($switch['roleString'])) {
				if(str_contains($switch['roleString'], 'admin')) {
					$additive             = str_replace('admin', null, $switch['roleString']);
					$switch['roleString'] = user()->userRolesArray('browse' , [] , Role::adminRoles() ) ;
					foreach($switch['roleString'] as $key => $value) {
						$switch['roleString'][ $key ] .= $additive;
					}
				}
				elseif(str_contains($switch['roleString'], 'auto')) {
					$additive             = str_replace('auto', null, $switch['roleString']);
					$switch['roleString'] = user()->userRolesArray();
					foreach($switch['roleString'] as $key => $value) {
						$switch['roleString'][ $key ] = $value . $additive;
					}
				}
			}

			$switch['roleString'] = (array) $switch['roleString'] ;

			$table->where( function($query) use ($switch) {
				$query->where('id' , '0') ;

				foreach($switch['roleString'] as $string) {
					$string = str_replace('.all' , null , $string);
					$string = self::deface($string) ;
					$query->orWhere('cache_roles' , 'like' , "%$string%");
				}

			});

		}


		/*-----------------------------------------------
		| Role ...
		*/
		if($switch['role'] and $switch['role'] != 'all') {
			if($switch['role']=='admin') {
				$switch['role'] = Role::adminRoles() ;
			}
			elseif($switch['role']=='auto') {
				$switch['role'] = user()->userRolesArray() ;
			}
			elseif($switch['role'] == 'no') {
				$table->has('roles', '=', 0);
			}
			elseif(!is_array($switch['role'])) {
				$switch['role'] = (array) $switch['role'];
			}

			if(is_array($switch['role']) and count($switch['role'])) {
				$table->whereHas('roles', function ($query) use ($switch) {
					$query->whereIn('roles.slug', $switch['role']);

					// Considering status...
					if($switch['status'] !== false ) {
						$query->where('role_user.status', intval($switch['status']));
					}
					if($switch['min_status'] !== false ) {
						$query->where('role_user.status', '>=' , intval($switch['min_status']));
					}
					if($switch['max_status'] !== false ) {
						$query->where('role_user.status', '<=' , intval($switch['max_status']));
					}

					// Considering Permissions...
					if($switch['permits'] !== false) {
						$switch['permits'] = (array) $switch['permits'] ;

						foreach($switch['permits'] as $request) {
							$request = str_replace(self::$wildcards, '', $request);
							$query->where('role_user.permissions', 'like', "%$request%");
						}
					}

					// considering banned order...
					if(!$switch['banned']) {
						$query->whereNull('role_user.deleted_at');
					}
					elseif($switch['banned'] !== 'all') {
						$query->whereNotNull('role_user.deleted_at');
					}


				});
			}
		}

		/*-----------------------------------------------
		| Domain ...
		*/
		if($switch['domain']) {
			if($switch['domain'] == 'all') {
				//nothing to do :)
			}
			else {
				//@TODO
			}
		}


		/*-----------------------------------------------
		| Trashed ...
		*/
		if($switch['bin']) {
			$table->onlyTrashed();
		}

		/*-----------------------------------------------
		| Criteria ...
		*/
		switch ($switch['criteria']) {
			case 'blocked' :

		}


		/*-----------------------------------------------
		| Search ...
		*/
		if($switch['search']) {
			$table = $table->whereRaw(self::searchRawQuery($switch['search']));
		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $table;


	}

	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/

	public function getAgeAttribute()
	{
		/*-----------------------------------------------
		| Bypass ...
		*/
		if(!$this->birth_date) {
			return false ;
		}

		/*-----------------------------------------------
		| Calculation ...
		*/
		return Carbon::now()->diffInYears($this->birth_date) ;


	}


	public function getProfileLinkAttribute()
	{
		return "manage/users/browse/all/search?id=$this->id&searched=1";
	}


	public function getMobileMaskedAttribute()
	{
		$string = $this->mobile;
		if(strlen($string) == 11) {
			return substr($string, 7) . ' ••• ' . substr($string, 0, 4);
		}

		return $string;
	}

	public function getMobileFormattedAttribute()
	{
		$string = $this->mobile;
		if(strlen($string) == 11) {
			return substr($string, 7) . ' - ' . substr($string, 4, 3) . ' - ' . substr($string, 0, 4);
		}

		return $string;
	}


	public function getFullNameAttribute()
	{
		if($this->exists) {
			return $this->name_first . ' ' . $this->name_last;
		}
		else {
			return trans('people.deleted_user');
		}
	}
	
	public function getMaritalNameAttribute()
	{
		switch($this->marital) {
			case 1 :
				return trans('forms.general.married') ;
			case 2 :
				return trans('forms.general.single') ;
			default:
				return trans("forms.general.unknown");

		}
	}

	public function getEduLevelAttribute($original_value)
	{
		return $original_value + 0 ;
	}


	public function getEduLevelNameAttribute()
	{
		return trans("people.edu_level_full.$this->edu_level") ;
	}

	public function getEduLevelShortAttribute()
	{
		return trans("people.edu_level_short.$this->edu_level") ;
	}




	public function _getStatusAttribute()
	{
		$request_role = $this->getChain('as');
		if($request_role) {
			if(!$this->as($request_role)->includeDisabled()->hasRole()) {
				return 'without';
			}
			elseif($this->as($request_role)->enabled()) {
				return 'active';
			}
			else {
				return 'blocked';
			}
		}
		else {
			if(!$this->trashed()) {
				return 'active';
			}
			else {
				return 'blocked';
			}
		}

	}

	public function getSumReceiptAmountAttribute()
	{
		return Receipt::where('user_id', $this->id)->sum('purchased_amount');
	}


	/*
	|--------------------------------------------------------------------------
	| Stators
	|--------------------------------------------------------------------------
	|
	*/

	public function preference($slug)
	{
		$this->spreadMeta();
		$preferences = array_normalize($this->preferences, [
			'max_rows_per_page' => "50",
		]);

		return $preferences[ $slug ];
	}

	public function canEdit()
	{
		//$request_role = $this->getChain('as');

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		if($this->id == user()->id) {
			return false ;
		}
		//elseif($this->is_an('admin')) {
		//	return user()->is_a('superadmin');
		//}

		/*-----------------------------------------------
		| Other Users ...
		*/
		foreach($this->as('all')->rolesArray() as $role_slug) {
			if(user()->as('admin')->can("users-$role_slug.edit"))
				return true ;
		}

		/*-----------------------------------------------
		| Just in case :) ...
		*/
		return false ;
	}

	public function canDelete()
	{
		$request_role = $this->getChain('as');

		/*-----------------------------------------------
		| Simple Decisions ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		if($this->id == user()->id) {
			return false ;
		}

		/*-----------------------------------------------
		| Other users ...
		*/
		if($request_role) {
			return user()->as($request_role)->can('delete');
		}
		else {
			foreach($this->as('all')->rolesArray() as $role_slug) {
				if(user()->as('admin')->can("users-$role_slug.delete")) {
					return true;
				}
			}
		}

		/*-----------------------------------------------
		| Just in case :) ...
		*/
		return false ;

	}

	public function canBin()
	{
		$request_role = $this->getChain('as');

		/*-----------------------------------------------
		| Simple Decisions ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		if($this->id == user()->id) {
			return false ;
		}

		/*-----------------------------------------------
		| Other users ...
		*/
		if($request_role) {
			return user()->as($request_role)->can('bin');
		}
		else {
			foreach($this->as('all')->rolesArray() as $role_slug) {
				if(user()->as('admin')->can("users-$role_slug.bin")) {
					return true;
				}
			}
		}

		/*-----------------------------------------------
		| Just in case :) ...
		*/
		return false ;

	}


	public function canPermit()
	{

		$request_role = $this->getChain('as');

		/*-----------------------------------------------
		| Simple Decisions ...
		*/
		if($this->trashed()) {
			return false;
		}
		if($this->id == user()->id) {
			return false;
		}
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		//if($this->as($request_role)->status()<8) {
		//	return false ;
		//}

		/*-----------------------------------------------
		| In case of a specified role ...
		*/
		if($request_role) {
			return user()->as($request_role)->can('permit');
		}

		/*-----------------------------------------------
		| In case of generally called ...
		*/
		if(!$request_role) {
			return user()->as_any()->can('users-all.permit');
		}

		/*-----------------------------------------------
		| Just in case :) ...
		*/
		return false ;

	}

	/*
	|--------------------------------------------------------------------------
	| Helpers
	|--------------------------------------------------------------------------
	|
	*/
	public function rolesTable()
	{
		return Role::all();
	}

	public function roleStatusCombo()
	{
		return [
			['', trans('people.without_role')],
			['active', trans('forms.status_text.active')],
			['blocked', trans('forms.status_text.blocked')],
		];

	}

}