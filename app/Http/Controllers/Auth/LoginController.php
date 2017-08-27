<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


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

    use AuthenticatesUsers {
        showLoginForm as protected traitShowLoginForm;
    }

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
        $this->middleware('guest', ['except' => ['logout', 'redirectAfterLogin']]);
    }

    public function redirectAfterLogin()
    {
        //@TODO: Complete this based on the user roles

        if (user()->is_an('admin')) {
            $defaultUrl = '/manage';
        } else {
            $defaultUrl = getLocale() . '/user/dashboard';
        }

        return redirect()->intended($defaultUrl);
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
        if ($logged_user) {
            $logged_user = decrypt($logged_user);
            Auth::loginUsingId($logged_user);
            return redirect('/manage');
        }

        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/');
    }

    public function showLoginForm()
    {
        $query = \request()->query();

        if (array_key_exists('redirect', $query)) {
            switch (strtolower($query['redirect'])) {
                case 'referer':
                    session(['url.intended' => url()->previous()]);
                    break;
                default:
                    if (!Validator::make([
                        'url' => $query['redirect']
                    ], [
                        'url' => 'required|url',
                    ])->fails()
                    ) {
                        session(['url.intended' => $query['redirect']]);
                    }
                    break;
            }
            session()->save();
        }

        return $this->traitShowLoginForm();
    }
}
