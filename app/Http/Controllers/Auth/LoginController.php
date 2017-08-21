<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout' , 'redirectAfterLogin']]);
    }

    public function redirectAfterLogin()
    {
        //@TODO: Complete this based on the user roles

        if (user()->is_an('admin'))
        {
            return redirect('/manage');
        }
        else
        {
            return redirect(url_locale('user/dashboard'));
        }
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'code_melli';
    }

	public function logout(Request $request)
	{
		$logged_user = session()->pull('logged_developer');
		if($logged_user) {
			$logged_user = decrypt($logged_user) ;
			Auth::loginUsingId($logged_user) ;
			return redirect('/manage');
		}

		$this->guard()->logout();

		$request->session()->flush();

		$request->session()->regenerate();

		return redirect('/');
	}
}
