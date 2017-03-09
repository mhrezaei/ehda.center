<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\RegisterSaveRequest;
use App\Models\Post;
use App\Models\User;
use App\Traits\TahaControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FrontController extends Controller
{
    use TahaControllerTrait;
    public function index()
    {
        $slideshow = Post::selector(['type' => 'slideshow'])->orderBy('id', 'desc')->get();
        return view('front.home.0', compact('slideshow'));
    }

    public function register(RegisterSaveRequest $request)
    {
        $input = $request->toArray();

        // check user exists
        $user = User::where('code_melli', $input['code_melli'])->first();
        if ($user)
        {
            if ($user->is_a('customer'))
            {
                return $this->jsonFeedback(null, [
                    'ok' => 1,
                    'message' => trans('front.relogin'),
                ]);
            }
            else
            {
                return $this->jsonFeedback(null, [
                    'ok' => 1,
                    'message' => trans('front.code_melli_already_exists'),
                ]);
            }
        }

        // store user to database
        $user = [
            'code_melli' => $input['code_melli'],
            'mobile' => $input['mobile'],
            'name_first' => $input['name_first'],
            'name_last' => $input['name_last'],
            'password' => Hash::make($input['password']),

        ];
        $store = User::store($user);

        if ($store)
        {
            // login user
            Auth::loginUsingId($store);

            // add customer role
            user()->attachRole('user');

            return $this->jsonFeedback(null, [
                'ok' => 1,
                'message' => trans('front.register_success'),
                'redirect' => url_locale('user/dashboard'),
            ]);
        }
        else
        {
            return $this->jsonFeedback(null, [
                'ok' => 0,
                'message' => trans('front.register_failed'),
            ]);
        }


    }
}
