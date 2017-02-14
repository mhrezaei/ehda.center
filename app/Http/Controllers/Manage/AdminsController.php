<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Traits\TahaControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminsController extends Controller
{
	use TahaControllerTrait ;

	public function __construct()
	{
		$this->page[0] = ['admins' , trans('people.admins.title')];
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
