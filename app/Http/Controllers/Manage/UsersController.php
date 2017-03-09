<?php

namespace App\Http\Controllers\Manage;

use App\Models\Role;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
	use ManageControllerTrait ;

	protected $Model ;
	protected $browse_counter ;
	protected $browse_selector ;
	protected $view_folder ;

	public function __construct()
	{
		$this->page[0] = ['users' , trans('people.site_users')];

		$this->Model = new User() ;
		$this->Model->setSelectorPara([
			'role' => "admin",
		]);

		$this->browse_handle = 'counter' ;
		$this->view_folder = 'manage.users' ;
	}

	public function browse($request_role, $request_tab = 'actives')
	{
		/*-----------------------------------------------
		| Check Permission ...
		*/
		if(!Role::checkManagePermission($request_role , $request_tab)) {
			return view('errors.403');
		}

		/*-----------------------------------------------
		| Revealing the Role...
		*/
		if($request_role!='all') {
			$role = Role::findBySlug($request_role);
			if(!$role->exists) {
				return view('errors.404');
			}
		}
		else {
			$role = new Role() ;
			$role->plural_title = trans('people.commands.all_users');
		}

		/*-----------------------------------------------
		| Page Browse ...
		*/
		$page = [
			'0' => ["users/browse/$request_role" , $role->plural_title , "users/browse/$request_role"],
		     '1' => [$request_tab , trans("people.criteria.$request_tab") , "users/browse/$request_role/$request_tab" ],
		];

		/*-----------------------------------------------
		| Model ...
		*/
		$selector_switches = [
			'role' => $request_role,
		     'criteria' => $request_tab ,
		];

		$models = User::selector($selector_switches)->orderBy('created_at', 'desc')->paginate(user()->preference('max_rows_per_page'));
		$db = $this->Model ;

		/*-----------------------------------------------
		| Views ...
		*/
		return view($this->view_folder . ".browse",compact('page','models','db','request_role','role'));

	}

	public function create($role_slug)
	{
		dd("create ".$role_slug);
	}
}
