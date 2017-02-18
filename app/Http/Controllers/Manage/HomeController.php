<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manage\ChangeSelfPasswordRequest;
use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
	use ManageControllerTrait ;
	private $page = array() ;


	public function __construct()
	{
		$this->page[0] = ['index' , trans('manage.dashboard')] ;
	}

	public function index()
	{
		$page = $this->page ;

		$string = "image title  text comment gallery  visibility_choice searchable  template_choice   preview" ;
		$array = array_filter(explode(' ', $string));

		ss($array);

		return view("manage.home.index",compact('page'));

	}

	public function account()
	{
		$page[0] = ['account' , trans('people.commands.change_password')];
		return view("manage.home.password",compact('page'));
	}

	public function changePassword(ChangeSelfPasswordRequest $request)
	{
		$session_key = 'password_attempts' ;
		$check = Hash::check($request->current_password , user()->password) ;


		if(!$check) {
			$session_value = $request->session()->get($session_key , 0) ;
			$request->session()->put($session_key , $session_value+1);
			if($session_value>3) {
				$request->session()->flush();
				$ok = 0 ;
			}
			else
				return $this->jsonFeedback(trans('forms.feed.wrong_current_password'));
		}
		else {
			$request->session()->forget($session_key);
			user()->password = bcrypt($request->new_password);
			$ok = user()->update();
		}

		return $this->jsonAjaxSaveFeedback($ok , [
			'success_redirect' => url('manage'),
			'danger_refresh' => true,
		]);


	}
}
