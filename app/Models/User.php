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

	public static $meta_fields = ['preferences'];
	protected     $guarded     = ['id'];
	protected     $hidden      = ['password', 'remember_token'];
	protected     $casts       = [
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

	public static function selector($parameters = [])
	{
		extract(array_normalize($parameters, [
			'role'     => "customer",
			'criteria' => "actives",
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
		| Return  ...
		*/

		return $table;
	}

	/*
	|--------------------------------------------------------------------------
	| Selectors
	|--------------------------------------------------------------------------
	|
	*/

	public function roles()
	{
		return $this->belongsToMany('App\Models\Role')->withPivot('permissions', 'deleted_at')->withTimestamps();;
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

	public function getAdminPositionAttribute()
	{
		if(!$this->hasRole('admin')) {
			return '-';
		}

		if($this->isDeveloper()) {
			return trans('people.admins.developer');
		}
		if($this->as('admin')->can('super')) {
			return trans('people.admins.super_admin');
		}
		else {
			return trans('people.admins.ordinary_admin');
		}
	}

	public function getStatusAttribute()
	{
		if($this->as_role) {
			if(!$this->includeDisabled()->hasRole()) {
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

		return $preferences[$slug];
	}

	public function canEdit()
	{
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
		if(!$this->as_role or $this->as_role == 'admin') {
			return user()->is_a('superadmin');
		}
		else {
			return Role::checkManagePermission($this->as_role, 'edit');
		}
	}

	public function canDelete()
	{
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
			['' , trans('people.without_role')],
			['active' , trans('forms.status_text.active')],
			['blocked' , trans('forms.status_text.blocked')],
		];

	}
}
