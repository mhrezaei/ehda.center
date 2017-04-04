<?php

namespace App\Models;

use App\Traits\PermitsTrait;
use App\Traits\TahaModelTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use Notifiable, TahaModelTrait, PermitsTrait, SoftDeletes;

	public static $meta_fields   = ['preferences'];
	public static $search_fields = ['name_first', 'name_last', 'name_firm', 'code_melli', 'email', 'mobile'];
	protected     $guarded       = ['id'];
	protected     $hidden        = ['password', 'remember_token'];
	protected     $casts         = [
		'meta'                  => 'array',
		'newsletter'            => 'boolean',
		'password_force_change' => 'boolean',
		'published_at'          => 'datetime',
	];

	/*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	|
	*/
	public function roles()
	{
		return $this->belongsToMany('App\Models\Role')->withPivot('permissions', 'deleted_at')->withTimestamps();;
	}


	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/
	public static function selector($parameters = [])
	{
		extract(array_normalize($parameters, [
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

	public function getFullNameAttribute()
	{
		if($this->exists) {
			return $this->name_first . ' ' . $this->name_last;
		}
		else {
			return trans('people.deleted_user');
		}
	}

	public function getStatusAttribute()
	{
		$request_role = $this->getAndResetAsRole();
		if($request_role) {
			if(!$this->as($request_role)->includeDisabled()->hasRole()) {
				return 'without';
			}
			elseif($this->enabled()) {
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
		$request_role = $this->getAndResetAsRole();

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
		$request_role = $this->getAndResetAsRole();

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
		$request_role = $this->getAndResetAsRole();

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
		$request_role = $this->getAndResetAsRole();

		/*-----------------------------------------------
		| Simple Decisions ...
		*/
		if($this->trashed()) {
			return false;
		}
		if($this->id == user()->id) {
			return false;
		}

		if($request_role) {
			if($this->as($request_role)->disabled()) {
				return false;
			}
			$role = Role::findBySlug($request_role);
			if(!$role or !$role->has_modules) {
				return false;
			}
		}

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
