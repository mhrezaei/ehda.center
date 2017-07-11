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
	public static $search_fields   = ['name_first', 'name_last', 'name_firm', 'code_melli', 'email', 'mobile'];
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
	public static function finder($username, $as_role = null, $username_field = 'auto')
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
			if($switch['role'] == 'all') {
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
		| Role ...
		*/
		if($switch['role']) {
			if($switch['role'] == 'all') {
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

	/**
	 * @deprecated
	 *
	 * @param array $parameters
	 *
	 * @return mixed
	 */
	protected static function __selector($parameters = [])
	{
		$switch = array_normalize($parameters, [
			'id'         => false,
			'email'      => false,
			'code_melli' => false,
			'mobile'     => false,
			'role'       => 'default', // <~~ Supports Arrays
			'status'     => false, // <~~ best works where only one role is given.
			'min_status' => false, // <~~ best works where only one role is given.
			'max_status' => false, // <~~ best works where only one role is given.
			'permits'    => false,  // <~~ Supports Arrays
			'search'     => false,
			'criteria'   => null,
			'banned'     => false,
			'bin'        => false,
			'domain'     => 'all',
		]);

		$table                 = self::where('id', '>', '0');
		$default_role_included = false;

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
		| Role ...
		*/
		if($switch['role']) {
			if($switch['role'] == 'default') {
				$switch['role'] = self::defaultRole();
			}
			if($switch['role'] == 'all' or $switch['role'] == self::defaultRole()) {
				if(self::defaultRole()) {
					$default_role_included = true;
					$table->where('default_role_status', '>', 0);
					$table->whereNull('default_role_deleted_at');
				}
				//nothing to do :)
			}
			elseif($switch['role'] == 'no') {
				if(self::defaultRole()) {
					$default_role_included = true;
					// nothing to do :)
				}
				else {
					$table->has('roles', '=', 0);
				}
			}
			elseif(!is_array($switch['role'])) {
				$switch['role'] = [$switch['role']];
			}

			if(is_array($switch['role']) and count($switch['role'])) {
				if(in_array(self::defaultRole(), $switch['role'])) {
					$default_role_included = true;
					$table->where('default_role_status', '>', 0);
				}
				else {
					$table->whereHas('roles', function ($query) use ($switch) {
						$query->whereIn('roles.slug', $switch['role']);
					});
				}
			}
		}

		/*-----------------------------------------------
		| Status ...
		*/
		if($switch['status'] !== false) {

			if($switch['status'] == 'all') {
				//nothing to do.
			}
			elseif($switch['status'] == 'bin') {
				if($switch['role'] == 'all') {
					$switch['bin'] = true;
				}
				else {
					$switch['banned'] = true;
				}
			}
			elseif($default_role_included) {
				$table->where('default_role_status', $switch['status']);
			}
			else {
				$table->whereHas('roles', function ($query) use ($switch) {
					$query->whereIn('roles.slug', $switch['role']);
					$query->where('role_user.status', $switch['status']);
				});
			}
		}

		if($switch['min_status'] !== false) {
			if($default_role_included) {
				$table->where('default_role_status', '>=', $switch['min_status']);
			}
			else {
				$table->whereHas('roles', function ($query) use ($switch) {
					$query->whereIn('roles.slug', $switch['role']);
					$query->where('role_user.status', '<=', $switch['max_status']);
					$query->where('role_user.status', '>=', $switch['min_status']);
				});
			}
		}
		if($switch['max_status'] !== false) {
			if($default_role_included) {
				$table->where('default_role_status', '>=', $switch['max_status']);
			}
			else {
				$table->whereHas('roles', function ($query) use ($switch) {
					$query->whereIn('roles.slug', $switch['role']);
					$query->where('role_user.status', '<=', $switch['max_status']);
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
		| Banned ...
		*/
		if($switch['banned']) {
			if($default_role_included) {
				//@TODO
			}
			else {
				$table->whereHas('roles', function ($query) use ($switch) {
					$query->whereIn('roles.slug', $switch['role']);
					$query->whereNotNull('role_user.deleted_at');
				});
			}
		}
		else {
			if($default_role_included) {
				//@TODO
			}
			else {
				$table->whereHas('roles', function ($query) use ($switch) {
					$query->whereIn('roles.slug', $switch['role']);
					$query->whereNull('role_user.deleted_at');
				});
			}
		}

		/*-----------------------------------------------
		| Trashed ...
		*/
		if($switch['bin']) {
			$table->onlyTrashed();
		}


		/*-----------------------------------------------
		| Permits ...
		*/
		if($switch['permits'] !== false) {
			if(!is_array($switch['permits'])) {
				$switch['permits'] = [$switch['permits']];
			}
			if(is_array($switch['permits']) and count($switch['permits'])) {
				foreach($switch['permits'] as $request) {
					$request = str_replace(self::$wildcards, '', $request);
					$table->whereHas('roles', function ($query) use ($request) {
						$query->where('role_user.permissions', 'like', "%$request%");
					});
				}
			}
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

	public static function _selector($parameters = [])
	{
		extract(array_normalize($parameters, [
			'id'       => "0",
			'role'     => "customer",
			'criteria' => "actives",
			'search'   => "",
		]));

		/*-----------------------------------------------
		| Process Roles...
		*/
		if($role == 'all') {
			$table         = User::where('id', '>', '0');
			$related_table = false;
		}
		else {
			$table         = Role::findBySlug($role)->users();
			$related_table = true;
		}

		/*-----------------------------------------------
		| Exclude Developers ...
		*/
		if(!user()->isDeveloper()) {
			$table = $table->whereNotIn('email', ['chieftaha@gmail.com', 'mr.mhrezaei@gmail.com']);
		}

		/*-----------------------------------------------
		| Process id ...
		*/
		if($id > 0) {
			$table = $table->where('users.id', $id);
		}


		/*-----------------------------------------------
		| Process Criteria ...
		*/
		switch ($criteria) {
			case 'all' :
				//nothing to do :)
				break;

			case 'actives':
				if($related_table) {
					$table = $table->wherePivot('deleted_at', null);
				}
				else {
					//nothing to do <~~
				}
				break;

			case 'banned':
				if($related_table) {
					$table = $table->wherePivot('deleted_at', '!=', null);
				}
				else {
					$table = $table->onlyTrashed();
				}
				break;

			case 'bin' :
				if($related_table) {
					$table = $table->wherePivot('deleted_at', '!=', null);
				}
				else {
					$table = $table->onlyTrashed();
				}
				break;

			default:
				if($related_table) {
					$table = $table->where('users.id', '0');
				}
				else {
					$table = $table->where('id', 0);
				}

		}

		/*-----------------------------------------------
		| Process Search ...
		*/
		$table = $table->whereRaw(self::searchRawQuery($search));


		/*-----------------------------------------------
		| Return  ...
		*/

		return $table;
	}


	/*
	|--------------------------------------------------------------------------
	| Accessors & Mutators
	|--------------------------------------------------------------------------
	|
	*/

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
		$request_role = $this->getChain('as');

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other Users ...
		*/
		if(!$request_role or $request_role == 'admin') {
			return user()->is_a('superadmin');
		}
		else {
			return Role::checkManagePermission($request_role, 'edit');
		}
	}

	public function canDelete()
	{
		$request_role = $this->getChain('as');

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_admin()) {
			//return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other users ... @TODO: complete this part
		*/

		return user()->is_a('superadmin');

	}

	public function canBin()
	{
		$request_role = $this->getChain('as');

		/*-----------------------------------------------
		| Power users ...
		*/
		if($this->is_a('developer')) {
			return user()->is_a('developer');
		}
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
		}

		/*-----------------------------------------------
		| Other users ... @TODO: complete this part
		*/

		return user()->is_a('superadmin');

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

		/*-----------------------------------------------
		| In case of a specified role ...
		*/
		if($request_role) {
			if($this->as($request_role)->disabled()) {
				return false;
			}
			else {
				return user()->as($request_role)->can('permit');
			}
		}

		/*-----------------------------------------------
		| In case of generally called ...
		*/
		if(!$request_role) {
			return user()->as_any()->can('users-all.permit');
		}

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