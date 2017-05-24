<?php
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;


trait PermitsTrait2
{
	protected static $wildcards         = ['', 'any', '*'];
	protected static $default_role      = 'admin';
	protected static $available_permits = ['browse', 'process', 'view', 'send', 'search', 'create', 'edit', 'publish', 'activate', 'report', 'delete', 'bin'];
	protected        $as                = null;
	protected        $include_disabled  = false;
	protected        $min_status        = 0;
	protected        $max_status        = false;

	/*
	|--------------------------------------------------------------------------
	| Background Factory
	|--------------------------------------------------------------------------
	|
	*/
	public function roles()
	{
		return $this->belongsToMany('App\Models\Role')->withPivot('permissions', 'status', 'deleted_at')->withTimestamps();
	}

	public function getRoles()
	{
		if(user()->id == $this->id) {
			$revealed_at = session()->get('logged_user_revealed_at', false);
			$roles       = session()->get('logged_user_roles', false);
			if(!$roles or !$revealed_at or $revealed_at < $this->updated_at) {
				$roles = $this->fetchRoles();
				session()->put('logged_user_roles', $roles);
				session()->put('logged_user_revealed_at', Carbon::now()->toDateTimeString());
			}
		}
		else {
			$roles = $this->fetchRoles();
		}

		return $roles;
	}

	private function fetchRoles() // <-- (From Database)
	{
		return collect($this->roles()->get());
	}

	private function getChain($variable)
	{
		$return          = $this->$variable;
		$this->$variable = false;

		return $return;
	}

	private function rolesPermits()
	{
		$permits = null;
		foreach($this->rolesQuery()->pluck('pivot.permissions') as $permit) {
			try {
				$permit = Crypt::decrypt($permit);
			} catch (DecryptException $e) {
				$permit = '';
			}

			$permits .= " " . $permit . " ";
		}
		return $permits;

	}

	private function rolesQuery()
	{
		/*-----------------------------------------------
		| Parameters ...
		*/
		$include_disabled = $this->getChain('include_disabled');
		$min_status       = $this->getChain('min_status');
		$max_status       = $this->getChain('max_status');
		$request_roles    = $this->getChain('as');

		if($request_roles and !is_array($request_roles)) {
			$request_roles = [$request_roles];
		}

		/*-----------------------------------------------
		| Query Buildup ...
		*/
		$query = $this->getRoles();

		if($request_roles) {
			$query = $query->whereIn('slug', $request_roles);
		}
		if($min_status) {
			$query = $query->where('pivot.status', '>=', $min_status);
		}
		if($max_status) {
			$query = $query->where('pivot.status', '<=', $max_status);
		}
		if(!$include_disabled) {
			$query = $query->where('pivot.deleted_at', null);
		}

		/*-----------------------------------------------
		| Return ...
		*/

		return $query;

	}

	/*
	|--------------------------------------------------------------------------
	| Usage Hooks
	|--------------------------------------------------------------------------
	|
	*/
	public function hasRole($request = null, $any_of_them = false)
	{
		/*-----------------------------------------------
		| Variable Setup ...
		*/
		if($request) {
			$this->as($request);
		}
		else {
			$request = $this->as;
		}

		/*-----------------------------------------------
		| Shortcuts ...
		*/
		if(in_array($request, ['developer', 'dev'])) {
			return $this->isDeveloper();
		}
		if($request == 'superadmin') {
			return $this->as('admin')->isSuper();
		}

		/*-----------------------------------------------
		| Simple Decisions ...
		*/
		if(!$this->exists) {
			return false;
		}
		if($this->id == user()->id and $this->isDeveloper()) {
			return true;
		}

		/*-----------------------------------------------
		| Final Decision ...
		*/
		$count = $this->rolesQuery()->count();

		if($any_of_them) {
			return boolval($count);
		}
		else {
			return boolval($count == count($request));
		}

	}

	public function isDeveloper()
	{
		return in_array($this->code_melli, ['0074715623', '0012071110', '0017263573']);
	}

	public function isSuper()
	{
		$role = $this->getChain('as');
		if($role) {
			return $this->as($role)->can('super');
		}
		else {
			return $this->as('admin')->can('super');
		}
	}

	public function can($request = '*', $blah = false)
	{
		/*-----------------------------------------------
		| Parameters ...
		*/
		$request = str_replace('post-', 'posts-', $request);
		$request = str_replace('comment-', 'comments-', $request);
		$request = str_replace('user-', 'users-', $request);
		if(in_array($request, self::$wildcards)) {
			$request = null;
		}
		foreach(self::$wildcards as $wildcard) {
			$request = str_replace($wildcard, '', $request);
		}

		/*-----------------------------------------------
		| Simple Decisions ...
		*/
		if($request == 'developer' or $request == 'dev') {
			return $this->isDeveloper();
		}
		if($request == 'super' or $request == 'superadmin') {
			$request = 'users-admin'; //@TODO: Find a better solution!
		}
		if(!$this->exists) {
			return false;
		}
		if($this->isDeveloper()) {
			return true;
		}

		/*-----------------------------------------------
		| Final Decisions ...
		*/

		return str_contains( $this->rolesPermits(), $request);
	}


	public function pivot()
	{
		//$first = $this->includeDisabled()->rolesQuery()->first() ;
		//if(!$first) {
		//	ss($this->as);
		//}
		//else {
		//	return $first->pivot ;
		//}
		return $this->includeDisabled()->rolesQuery()->first()->pivot ;
	}


	/*
	|--------------------------------------------------------------------------
	| Chain Methods
	|--------------------------------------------------------------------------
	|
	*/

	public function includeDisabled()
	{
		$this->include_disabled = true;

		return $this;
	}

	public function withDisabled()
	{
		return $this->includeDisabled();
	}


	public function as ($requested_role)
	{
		if($requested_role == 'all') {
			$this->as = false;
		}
		else {
			$this->as = $requested_role;
		}

		return $this;
	}


	public function min($min_status = 1)
	{
		$this->min_status = $min_status;

		return $this;
	}

	public function max($max_status = 1)
	{
		$this->max_status = $max_status;

		return $this;
	}


	/*
	|--------------------------------------------------------------------------
	| Beautiful Mirrors
	|--------------------------------------------------------------------------
	|
	*/
	public function is_a($requested_role)
	{
		return $this->hasRole($requested_role);
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

	public function is_one_of($requested_roles)
	{
		return $this->hasRole($requested_roles, true);
	}

	public function is_any_of($requested_roles)
	{
		return $this->hasRole($requested_roles, true);
	}

	public function is_not_one_of($requested_roles)
	{
		return !$this->hasRole($requested_roles, true);
	}

	public function is_not_any_of($requested_roles)
	{
		return !$this->hasRole($requested_roles, true);
	}

	public function is_all_of($requested_roles)
	{
		return $this->hasRole($requested_roles, false);
	}

	public function is_none_of($requested_roles)
	{
		return !$this->hasRole($requested_roles, false);
	}

	public function is_not_all_of($requested_roles)
	{
		return !$this->hasRole($requested_roles, false);
	}


	public function as_any()
	{
		return $this->as('all');
	}

	public function cant($request = '*', $blah = false)
	{
		return !$this->can($request);
	}

	public function cannot($request = '*', $blah = false)
	{
		return !$this->can($request);
	}

	public function can_all($requests)
	{
		foreach($requests as $request) {
			if($this->cannot($request)) {
				return false;
			}
		}

		return true;
	}

	public function can_any($requests)
	{
		foreach($requests as $request) {
			if($this->can($request)) {
				return true;
			}
		}

		return false;
	}

	public function disabled()
	{
		return boolval($this->pivot()->deleted_at);
	}

	public function enabled()
	{
		return !$this->disabled() ;
	}

	public function shh()
	{
		return null;
	}


}