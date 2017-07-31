<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\NewPasswordRequest;
use App\Http\Requests\Front\PasswordTokenRequest;
use App\Http\Requests\Front\ResetPasswordRequest;
use App\Models\User;
use App\Providers\EmailServiceProvider;
use App\Providers\SettingServiceProvider;
use App\Traits\ManageControllerTrait;
use Asanak\Sms\Facade\AsanakSms;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Mockery\Generator\StringManipulation\Pass\Pass;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    use ManageControllerTrait;

    public static $resetTokenLength = 6;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * modify
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(ResetPasswordRequest $request)
    {
//        dd(__FILE__);

//        $this->validate($request, ['email' => 'required|email']);

        $user = User::where([
            'code_melli' => $request->code_melli,
            $request->type => $request[$request->type]
        ])->first();

        if (!$user) {
            return $this->jsonAjaxSaveFeedback(false, [
                'ok' => 0,
                'danger_message' => trans('passwords.user'),
            ]);
        }

        $resetToken = rand(str_repeat(1, self::$resetTokenLength), str_repeat(9, self::$resetTokenLength));

        User::store([
            'id' => $user->id,
            'reset_token' => Hash::make($resetToken),
            'reset_token_expire' => Carbon::now()->addMinute(setting()->ask('password_token_expire_time')->gain())->toDateTimeString(),
        ]);

        session()->flash('resetingPasswordNationalId', $user->code_melli);
//        session(['resetingPasswordNationalId' => $user->code_melli]);

        $sendingSuccess = false;
        switch ($request['type']) {
            case 'email':
                // We will send the password reset link to this user. Once we have attempted
                // to send the link, we will examine the response then see the message we
                // need to show to the user. Finally, we'll send out a proper response.
//                $response = $this->broker()->sendResetLink(
//                    $request->only('email')
//                );
//
//                return $response == Password::RESET_LINK_SENT
//                    ? $this->sendResetLinkResponse($response)
//                    : $this->sendResetLinkFailedResponse($request, $response);

                $emailText = str_replace('::token', $resetToken, trans('passwords.reset_mail'))
                    . "\n\r"
                    . setting()->ask('site_url')->gain();

                $sendingResult = EmailServiceProvider::send($emailText, $user->email, trans('front.site_title'), trans('people.form.recover_password'), 'default_email');

                break;

            case 'mobile' :
                $smsText = str_replace('::token', $resetToken, trans('passwords.reset_sms'))
                    . "\n\r"
                    . setting()->ask('site_url')->gain();

                $sendingResult = AsanakSms::send($user->mobile, $smsText);
//                file_put_contents('passwordToken.txt', $smsText);
//                $sendingResult = '["111"]';

                $sendingResult = json_decode($sendingResult);
                if ($sendingResult and is_array($sendingResult) and is_numeric($sendingResult[0])) {
                    $sendingSuccess = true;
                }
                break;
        }

        if ($sendingSuccess) {
            return $this->jsonAjaxSaveFeedback(true, [
                'success_message' => trans('passwords.sent'),
                'success_redirect' => url(SettingServiceProvider::getLocale() . '/password/token'),
                'redirectTime' => 3000,
            ]);
        } else {
            return $this->jsonAjaxSaveFeedback(false, [
                'danger_message' => trans('passwords.sending_problem'),
            ]);
        }
    }

    public function getToken($lang, $haveCode = null)
    {
        if (session()->get('resetingPasswordNationalId') or ($haveCode == 'code')) {
            session()->keep(['resetingPasswordNationalId']);
            return view('auth.passwords.token', compact('haveCode'));
        } else {
            return redirect(SettingServiceProvider::getLocale() . '/password/reset');
        }
    }

    public function checkToken(PasswordTokenRequest $request)
    {
        if ($request->has('code_melli')) {
            $user = User::where([
                'code_melli' => $request->code_melli,
            ])->first();

            $user->spreadMeta();

            $now = Carbon::now();
            $exp = Carbon::parse($user->reset_token_expire);

            if (Hash::check($request->password_reset_token, $user->reset_token)) {
                if ($now->lte($exp)) {
                    session(['resetPasswordVerifiedUser' => $user->code_melli]);

                    return $this->jsonAjaxSaveFeedback(true, [
                        'success_message' => trans('passwords.token_verified'),
                        'success_redirect' => url(SettingServiceProvider::getLocale() . '/password/new'),
                        'redirectTime' => 3000,
                    ]);

                } else {
                    return $this->jsonAjaxSaveFeedback(false, [
                        'danger_message' => trans('passwords.token_expired') . ' <br /><a href="' . url(SettingServiceProvider::getLocale() . '/password/reset') . '">' . trans('passwords.get_new_token') . '</a>'
                    ]);
                }
            } else {
                return $this->jsonAjaxSaveFeedback(false, [
                    'danger_message' => trans('passwords.token')
                ]);
            }
        }

        return $this->jsonAjaxSaveFeedback(false, [
            'danger_redirect' => SettingServiceProvider::getLocale() . '/password/reset'
        ]);
    }

    public function newPassword()
    {
        if (session()->get('resetPasswordVerifiedUser')) {
            return view('auth.passwords.new');
        } else {
            return redirect(SettingServiceProvider::getLocale() . '/password/reset');
        }
    }

    public function changePassword(NewPasswordRequest $request)
    {
        if (session()->get('resetPasswordVerifiedUser')) {
            $user = User::where([
                'code_melli' => session()->get('resetPasswordVerifiedUser'),
            ])->first();


            if ($user) {
                $storeData = [
                    'id' => $user->id,
                    'password' => Hash::make($request['new_password']),
                ];

                Auth::loginUsingId($user->id);

                if (user()->is_an('admin')) {
                    $targetUrl = url('/manage');
                } else {
                    $targetUrl = url_locale('user/dashboard');
                }

                return $this->jsonAjaxSaveFeedback(User::store($storeData), [
                    'success_message' => trans('passwords.reset'),
                    'success_redirect' => $targetUrl,
                    'redirectTime' => 3000,
                ]);
            }
        }

        return $this->jsonAjaxSaveFeedback(false, [
            'danger_redirect' => SettingServiceProvider::getLocale() . '/password/reset'
        ]);
    }
}
