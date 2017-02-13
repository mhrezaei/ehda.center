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
//		Auth::user()->detachRoles(['admin','user']);

		return view('templates.say' , ['array'=>[
			'hasRole()' => Auth::user()->hasRole(['super','asd'],1),
//			'original' => Auth::user()->getRoles()->toArray(),
		]]);

		return view("manage.home.index",compact('page'));

	}
}
