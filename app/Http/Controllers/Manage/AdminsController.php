<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminsController extends Controller
{
	use ManageControllerTrait ;

	protected $Model ;
	protected $browse_counter ;
	protected $browse_selector ;
	protected $view_folder ;

	public function __construct()
	{
		$this->page[0] = ['admins' , trans('people.admins.title')];

		$this->Model = new User() ;
		$this->browse_counter = true ;
		$this->browse_selector = false ;
		$this->view_folder = "manage.admins" ;

	}

	public function browse($request_tab = 'actives')
	{
		//Preparations...
		$page = $this->page ;
		$page[1] = ["browse/$request_tab" , trans("manage.tabs.$request_tab") , $request_tab] ;

		//Model...
		$models = User::selector([
			'role' => "admin",
			'criteria' => $request_tab,
		])->orderBy('created_at' , 'desc')->paginate(user()->preference('max_rows_per_page'));;
		$db = new User() ;
		$db->setSelectorPara([
			'role' => "admin",
		]);

		//View...
		return view("manage.admins.browse",compact('page' , 'models' , 'db'));

	}

}
