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

trait PermitsTrait
{

	protected static $wildcards = [ '' , 'any' , '*'] ;
	protected static $default_role = 'admin' ;
	protected $as_role = 'admin' ;
	protected static $available_permits = [
			'browse' ,
			'process' ,
			'view',
			'send',
			'search',
			'create',
			'edit',
			'publish',
			'activation',
			'report',
			'cats',
			'delete',
			'bin',
	];

	/*
	|--------------------------------------------------------------------------
	| Private Factory
	|--------------------------------------------------------------------------
	|
	*/

	private function getRoles()//@TODO: Should be private
	{
		$revealed_at = session()->get('revealed_at' , false);
		$roles = session()->get('roles' , false) ;
		if(!$roles or !$revealed_at or $revealed_at < $this->updated_at) {
			$roles = collect($this->roles()->get()) ;
			session()->put('roles' , $roles);
			session()->put('revealed_at' , Carbon::now()->toDateTimeString() );
		}

		return $roles ;


	}

	/*
	|--------------------------------------------------------------------------
	| Attach and Detach Roles
	|--------------------------------------------------------------------------
	|
	*/
	public function attachRoles($roles, $permissions = null)
	{
		//Getting the roles table...
		if(is_array($roles)) {
			$permissions = $roles ;
			list($slug_list, $values) = array_divide($roles);
			$roles = Role::whereIn('slug', $slug_list)->get();
		}
		else
			$roles = Role::where('slug' , $roles)->get() ;

		//Attaching one by one...
		foreach($roles as $role) {
			$this->roles()->detach($role->id);
			$this->roles()->attach($role->id, ['permissions' => is_array($permissions)?  $permissions[$role->slug] : $permissions]);
		}

		//Updating Users row...
		$this->updated_at = Carbon::now()->toDateTimeString() ;
		$this->update() ;
		return 1 ;

	}

	public function detachRoles($roles)
	{
		//Getting the roles table...
		if(is_array($roles))
			$roles = Role::whereIn('slug' , $roles)->get() ;
		else
			$roles = Role::where('slug' , $roles)->get() ;

		//Detaching one by one...
		$id_list = [] ;
		foreach($roles as $role) {
			array_push($id_list,$role->id);
		}
		$this->roles()->detach($id_list);

		//Updating Users row...
		$this->updated_at = Carbon::now()->toDateTimeString() ;
		$this->update() ;

	}


	/*
	|--------------------------------------------------------------------------
	| Inquiries
	|--------------------------------------------------------------------------
	|
	*/
	public function isDeveloper()
	{
//		return false ;
		return in_array($this->code_melli , ['0074715623' , '0012071110' ]) ;
	}

	public function hasRole($requested_roles , $any_of_them = false)
	{
		//Developer Exceptions...
		if($this->isDeveloper())
			return true ;

		if($requested_roles == 'developer')
			return $this->isDeveloper() ;

		//If only one role is requested...
		if(!is_array($requested_roles))
			return $this->getRoles()->where('slug',$requested_roles)->count();


		//If an array of roles is given...
		$count = $this->getRoles()->whereIn('slug',$requested_roles)->count();
		if($any_of_them)
			return $count ;
		else
			return $count == count($requested_roles);

	}

	public function as ($requested_role)
	{
		$this->as_role = $requested_role ;
		return $this ;

		$role = $this->getRoles()->where('slug' , $requested_role)->first();
		if(!$role)
			$this->as_role = 'no' ;
		else
			$this->as_role = $role->pivot->permissions ;

		return $this ;

	}

	public function can($requested_permission='*', $reserved=false)
	{
		//Special Situations...
		if($requested_permission=='developer' or $requested_permission=='dev')
			return $this->isDeveloper() ;

		//Simple decisions...
		if($this->isDeveloper())
			return true ;

		if(!$this->hasRole($this->as_role))
			return false;

		//Module Check...
		$permissions = $this->getRoles()->where('slug' , $this->as_role)->first()->pivot->permissions;

		if($permissions == 'super')
			return true ;

		if(in_array($requested_permission , self::$wildcards))
			return true;

		return str_contains($permissions , $requested_permission);


	}
}