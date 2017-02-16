<?php

namespace App\Http\Controllers\Manage;

use App\Models\User;
use App\Traits\ManageControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UpstreamController extends Controller
{
	use ManageControllerTrait;

	public function loginAs(Request $request)
	{
		$user = User::find($request->id) ;
		if(!$user->hasRole('admin'))
			return $this->jsonFeedback('user is not as admin');


		session()->put('logged_developer' , encrypt(user()->id)) ;
		$ok = Auth::loginUsingId( $user->id );
		return $this->jsonAjaxSaveFeedback($ok , [
				'success_redirect' => url('/manage'),
		]);

	}

}
