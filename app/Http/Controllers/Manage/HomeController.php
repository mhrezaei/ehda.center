<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	private $page = array() ;


	public function __construct()
	{
		$this->page[0] = ['index' , trans('manage.dashboard')] ;
	}

	public function index()
	{
		$page = $this->page ;

//		Auth::user()->attachRoles([
//			'admin' => "folan",
//			'user' => "sdfsdf",
//		]);

//		return view('templates.say' , ['array'=>[
//			'as()' => Auth::user()->as('user')->can('folan'),
//			'can()' => Auth::user()->can('any'),
//			'original' => Auth::user()->getRoles()->toArray(),
//		]]);

		return view("manage.home.index",compact('page'));

	}
}
