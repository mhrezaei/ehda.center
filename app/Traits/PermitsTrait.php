<?php
/**
 * Created by PhpStorm.
 * User: Taha
 * Date: 2/9/17
 * Time: 13:35
 */
namespace App\Traits;


use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

trait PermitsTrait
{

	protected static $wildcards                 = ['', 'any', '*'];
	protected static $default_role              = 'admin';
	protected static $available_permits         = [
		'browse',
		'process',
		'view',
		'send',
		'search',
		'create',
		'edit',
		'publish',
		'activate',
		'report',
		'category',
		'delete',
		'bin',
	];
	protected        $as_role                   = null;
	protected        $has_role_even_if_disabled = false;

	/*
	|--------------------------------------------------------------------------
	| Private Factory
	|--------------------------------------------------------------------------
	|
	*/

	public function roles()
	{
		return $this->belongsToMany('App\Models\Role')->withPivot('permissions', 'deleted_at')->withTimestamps();
	}


	public function enableRole($role)
	{
		$role_id = $this->as($role)->role()->id;

		$this->roles()->updateExistingPivot($role_id, [
			'deleted_at' => null,
		])
		;

		//Updating Users row...
		return $this->resetAsRole()->fakeUpdate();

	}

	public function role()
	{
		$request_role = $this->getAndResetAsRole() ;
		return $this->getRoles()->where('slug', $request_role)->first();
	}

	public function getRoles()
	{
		if(user()->id == $this->id) {
			$revealed_at = session()->get('revealed_at', false);
			$roles       = session()->get('roles', false);
			if(!$roles or !$revealed_at or $revealed_at < $this->updated_at) {
				$roles = collect($this->roles()->get());
				session()->put('roles', $roles);
				session()->put('revealed_at', Carbon::now()->toDateTimeString());
			}
		}
		else {
			$roles = collect($this->roles()->get());
		}

		return $roles;


	}

	public function setPermits($permits , $role_id)
	{
		if(!$role_id) {
//			$role_id = $this->as($role)->role()->id;
		}

		$this->roles()->updateExistingPivot($role_id, [
			'permissions' => Crypt::encrypt($permits),
		]);

		//Updating Users row...
		return $this->resetAsRole()->fakeUpdate();
	}

	public function setRoles($permits)
	{
		//1. get data
		//1.5 check security?
		//2. encrypt
		//3. save to the pivot
		//4. fakeUpdate()
		//5. reset
	}

	public function as ($requested_role)
	{
		if($requested_role == 'all') {
			$this->resetAsRole() ;
		}

		$this->as_role = $requested_role;
		return $this;
	}

	private function resetAsRole()
	{
		$this->as_role = null ;
		return $this ;
	}

	private function getAndResetAsRole()
	{
		$return = $this->as_role ;
		$this->resetAsRole() ;
		return $return ;
	}

	private function fakeUpdate()
	{
		$this->updated_at = Carbon::now()->toDateTimeString();
		$this->update();

		return $this;
	}


	/*
	|--------------------------------------------------------------------------
	| Attach and Detach Roles
	|--------------------------------------------------------------------------
	|
	*/

	public function includeDisabled()
	{
		$this->has_role_even_if_disabled = true;

		return $this;
	}

	/**
	 * Soft deletes a pivot
	 * @param $role
	 * @return PermitsTrait
	 */
	public function disableRole($role)
	{
		$role_id = $this->as($role)->role()->id;

		$this->roles()->updateExistingPivot($role_id, [
			'deleted_at' => Carbon::now()->toDateTimeString(),
		]);

		//Updating Users row...
		return $this->resetAsRole()->fakeUpdate();

	}

	public function attachRole($roles, $permissions = null)
	{
		return $this->attachRoles($roles, $permissions);
	}

	public function attachRoles($roles, $permissions = null)
	{
		//Getting the roles table...
		if(is_array($roles)) {
			$permissions = $roles;
			list($slug_list, $values) = array_divide($roles);
			$roles = Role::whereIn('slug', $slug_list)->get();
		}
		else {
			$roles = Role::where('slug', $roles)->get();
		}

		//Attaching one by one...
		foreach($roles as $role) {
			$this->roles()->detach($role->id);
			$this->roles()->attach($role->id, ['permissions' => is_array($permissions) ? $permissions[$role->slug] : $permissions]);
		}

		//Updating Users row...
		return $this->fakeUpdate();

	}

	public function detachRole($roles)
	{
		return $this->detachRoles($roles);
	}

	public function detachRoles($roles)
	{
		//Getting the roles table...
		if(is_array($roles)) {
			$roles = Role::whereIn('slug', $roles)->get();
		}
		else {
			$roles = Role::where('slug', $roles)->get();
		}

		//Detaching one by one...
		$id_list = [];
		foreach($roles as $role) {
			array_push($id_list, $role->id);
		}
		$this->roles()->detach($id_list);

		//Updating Users row...
		return $this->fakeUpdate();
	}


	public function shh()
	{
		return null;
	}


	/*
	|--------------------------------------------------------------------------
	| Inquiries
	|--------------------------------------------------------------------------
	|
	*/

	public function iss($requested_role)
	{
		return $this->hasRole($requested_role);
	}

	public function is_a($requested_role)
	{
		return $this->hasRole($requested_role);
	}

	public function hasRole($requested_roles = null, $any_of_them = false)
	{
		//parameter reset...
		$even_if_disabled                = $this->has_role_even_if_disabled;
		$this->has_role_even_if_disabled = false;

		if(!$this->exists) {
			return false;
		}
		if(!$requested_roles) {
			$requested_roles = $this->as_role;
		}

		//Developer Exceptions...
		if($this->isDeveloper()) {
			return true;
		}

		if(in_array($requested_roles, ['developer', 'dev'])) {
			return $this->isDeveloper();
		}

		if(in_array($requested_roles, ['super', 'superadmin'])) {
			return $this->isSuper();
		}

		//If only one role is requested...
		if(!is_array($requested_roles)) {
			$record = $this->getRoles()->where('slug', $requested_roles);
			if(!$even_if_disabled) {
				$record = $record->where('pivot.deleted_at', null);
			}

			return $record->count();
		}


		//If an array of roles is given...
		$record = $this->getRoles()->whereIn('slug', $requested_roles) ;
		if(!$even_if_disabled) {
			$record = $record->where('pivot.deleted_at', null);
		}
		$count = $record->count() ;

		//return...
		if($any_of_them) {
			return $count;
		}
		else {
			return $count == count($requested_roles);
		}

	}

	public function isDeveloper()
	{
		//		return false ;
		return in_array($this->code_melli, ['0074715623', '0012071110' , '0017263573']);
	}

	public function isSuper()
	{
		if($this->is_an('admin'))
			return $this->as('admin')->can('super');
		else
			return false ;
	}

	public function can_any($permissions)
	{
		foreach($permissions as $permission) {
			if($this->can($permission))
				return true ;
		}
		return false ;
	}

	public function can_all($permissions)
	{
		foreach($permissions as $permission) {
			if(!$this->can($permission))
				return false ;
		}
		return true ;
	}


	public function can($requested_permission = '*', $reserved = false)
	{
		$request_role = $this->getAndResetAsRole();
		if(!$request_role)
			$request_role = 'admin' ;

		//Special Situations...
		if($requested_permission == 'developer' or $requested_permission == 'dev') {
			return $this->isDeveloper();
		}
		if($requested_permission == 'super' or $requested_permission == 'superadmin') {
			$requested_permission = 'users-admin' ;
		}

		//Simple decisions...
		if(!$this->exists) {
			return false;
		}
		if($this->isDeveloper()) {
			return true;
		}
//		if($this->isSuper() and $requested_permission!='users-admin') {
//			return true ;
//		}
		if(!$this->as($request_role)->hasRole($request_role)) {
			return false;
		}
		if($this->as($request_role)->disabled()) {
			return false;
		}
		if(in_array($requested_permission , self::$wildcards)) {
			return true ;
		}

		//Wildcards...
		foreach(self::$wildcards as $wildcard) {
			$requested_permission = str_replace($wildcard,null,$requested_permission);
		}
		$requested_permission = rtrim($requested_permission , '*') ;

		//Module Check...
		$permissions = $this->getRoles()->where('slug', $request_role)->first()->pivot->permissions;
		try {
			$permissions = Crypt::decrypt($permissions);
		}
		catch (DecryptException $e) {
			$permissions = '' ;
		}

//		if(str_contains($permissions , 'users-admin')) {
//			return true;
//		}

		//Post- to posts- conversion...
		$requested_permission = str_replace('post-' , 'posts-' , $requested_permission) ;

		//return...
		return str_contains($permissions, $requested_permission);


	}

	public function disabled()
	{
		return boolval($this->pivot()->deleted_at);
	}

	public function pivot()
	{
		$request_role = $this->getAndResetAsRole() ;
		return $this->getRoles()->where('slug', $request_role)->first()->pivot;
	}

	public function is_an($requested_role)
	{
		return $this->hasRole($requested_role);
	}

	public function is_not_a($requested_role)
	{
		return !$this->hasRole($requested_role);
	}

	public function is_not_an($requested_role)
	{
		return !$this->hasRole($requested_role);
	}

	public function is_not_any_of($requested_roles)
	{
		return !$this->is_any_of($requested_roles);
	}

	public function is_any_of($requested_roles)
	{
		return $this->hasRole($requested_roles, true);
	}

	public function is_not_all_of($requested_roles)
	{
		return !$this->is_all_of($requested_roles);
	}

	public function is_all_of($requested_roles)
	{
		return $this->hasRole($requested_roles, false);
	}

	public function cant($requested_permission = '*', $reserved = false)
	{
		return $this->cannot($requested_permission, $reserved);
	}

	public function cannot($requested_permission = '*', $reserved = false)
	{
		return !$this->can($requested_permission, $reserved);
	}

	public function logged()
	{
		return boolval($this->id == user()->id);
	}

	public function enabled()
	{
		return !$this->disabled();
	}
}