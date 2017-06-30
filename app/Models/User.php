<?php

namespace App\Models;

use App\Providers\DummyServiceProvider;
use App\Traits\EhdaUsersTrait;
use App\Traits\PermitsTrait;
use App\Traits\PermitsTrait2;
use App\Traits\TahaModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class User extends Authenticatable
{
	use Notifiable, TahaModelTrait, SoftDeletes , PermitsTrait2 ;
	use EhdaUsersTrait ;

	public static $meta_fields     = [
		'preferences',
		'name_father',
		'home_tel',
		'postal_code',
		'address',
		'reset_token',
		'key',
		'default_role_deleted_at',
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
	public static function selector($parameters = [])
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
					//nothing to do :)
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
				$table->where('status', $switch['status']);
			}
			else {
				$table->whereHas('roles', function ($query) use ($switch) {
					$query->where('role_user.status', $switch['status']);
				});
			}
		}

		if($switch['min_status'] !== false) {
			if($default_role_included) {
				$table->where('status', '>=', $switch['min_status']);
			}
			else {
				$table->whereHas('roles', function ($query) use ($switch) {
					$query->where('role_user.status', '>=', $switch['min_status']);
				});
			}
		}
		if($switch['max_status'] !== false) {
			if($default_role_included) {
				$table->where('status', '>=', $switch['max_status']);
			}
			else {
				$table->whereHas('roles', function ($query) use ($switch) {
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
				$table->whereHas('roles', function ($query) {
					$query->whereNotNull('role_user.deleted_at');
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
		elseif($this->is_an('admin')) {
			return user()->is_a('superadmin');
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
				return user()->as($request_role)->can('permit') ;
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

	public function totalReceiptsAmountInEvent($post)
	{
		return $post->receipts->where('user_id', $this->id)->sum('purchased_amount');
	}

	public function drawingRecentScores($eventsNumber, $historyLimit = 0)
	{
		return Post::where([
			'type'   => 'events',
			'locale' => getLocale(),
		])->whereDate('starts_at', '<=', Carbon::now())
			->whereDate('ends_at', '>=', Carbon::now()->subDay($historyLimit))
			->leftJoin('receipts', [
				['starts_at', '<=', 'purchased_at'],
				['ends_at', '>=', 'purchased_at'],
				['user_id', '=', DB::raw(user()->id)],
			])
			->select(DB::raw('posts.*, sum(receipts.purchased_amount) as sum_amount'))
			->groupBy(DB::raw('posts.id'))
			->limit($eventsNumber)
			->orderBy('sum_amount', 'DESC')
			->get()
			;

	}

    /*
    |--------------------------------------------------------------------------
    | card holder
    |--------------------------------------------------------------------------
    |
    */

    public function cards($type = 'mini', $mode = 'show')
    {
        $card_type = ['mini', 'single', 'social', 'full'];
        $card_mode = ['show', 'download', 'print'];

        if (!in_array($type, $card_type))
        {
            $type = 'mini';
        }

        if (!in_array($mode, $card_mode))
        {
            $mode = 'show';
        }

        return url('/card/show_card/' . $type . '/' . hashid_encrypt($this->id, 'ids') . '/' . $mode);

    }
}
