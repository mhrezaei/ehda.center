<?php

namespace App\Http\Controllers\Front;

use App\Models\Post;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Providers\FaGDServiceProvider;
use App\Providers\SecKeyServiceProvider;
use App\Traits\TahaControllerTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mockery\CountValidator\Exception;
use Morilog\Jalali\Facades\jDate;

class CardController extends Controller
{
    use TahaControllerTrait;

    public function index()
    {
        $captcha = SecKeyServiceProvider::getQuestion('fa');
        $post = Post::findBySlug('organ-donation-card');
        $states = State::combo();
        return view('front.card_info.0', compact('captcha', 'post', 'states'));
    }

    public function register()
    {
        $states = State::combo();
        $input = Session::get('register_first_step');
        if (!$input) {
            return redirect('/organ_donation_card');
        }
        return view('site.card_register.0', compact('input', 'states'));
    }

    private function register_first_step($request)
    {
        $checkResult = $this->checkCodeMelli($request->code_melli);
        if (!$checkResult['canRegister']) {
            return $checkResult['response'];
        }

        // @TODO: verify "code_melli" with "name_first" and "name_last"
        $currentSession = session()->get('register_card') ?: [];
        $currentSession[$request->code_melli] = ['verified' => true, 'step' => 1];
        session()->put('register_card', $currentSession);

        return $this->jsonFeedback(null, [
            'ok'           => 1,
            'message'      => trans('forms.feed.wait'),
            'feed_timeout' => 1000,
            'callback'     => <<<JS
                upToStep(2);
JS
        ]);

//
//        $can_login = $user->exists and $user->isActive('volunteer') and $user->isActive('card');
//        // @TODO: check if can loogin
////        if (!$can_login) {
//        if (true) {
//            Session::put('register_first_step', $input);
//            return $this->jsonFeedback(null, [
////                'redirect' => url('register'),
//                'ok'      => 1,
//                'message' => trans('forms.feed.wait'),
//            ]);
//        } else {
//            return $this->jsonFeedback(null, [
//                'redirect' => url('relogin'),
//                'ok'       => 1,
//                'message'  => trans('forms.feed.wait'),
//            ]);
//        }

    }

    private function register_second_step($request)
    {
        $submittedIDs = session()->get('register_card');
        if (!array_key_exists($request->code_melli, $submittedIDs)) { // This "code_melli" hasn't been submitted
            // Achieving this condition means that "code_melli" has been manipulated in read only mode by client
            return $this->jsonFeedback(null, [
                'ok'      => 0,
                'refresh' => 1,
            ]);
        }

        $checkResult = $this->checkCodeMelli($request->code_melli);
        if (!$checkResult['canRegister']) {
            return $checkResult['response'];
        }


        $state = State::find($request->home_city);
        $modifyingData = [
//            'card_status'           => 8, // @TODO: ask what to do
            'password'              => Hash::make($request->password),
            'home_province'         => $state->province()->id,
            'domain'                => $state->domain->slug,
            'password_force_change' => 0,
//            'organs'                => 'Heart Lung Liver Kidney Pancreas Tissues', // @TODO: ask what to do
        ];

        $request->merge($modifyingData);

        $input = $request->all();
        unset(
            $input['_token'],
            $input['_submit'],
            $input['password2']
        );
        $submittedIDs[$request->code_melli]['step'] = 2;
        $submittedIDs[$request->code_melli]['data'] = $input;
        session()->put('register_card', $submittedIDs);

        return $this->jsonFeedback(trans('forms.feed.register_check_data_step_second'), [
            'ok'       => 1,
            'callback' => <<<JS
                upToStep(3);
JS
        ]);

    }

    public function register_third_step($request)
    {
        $submittedIDs = session()->get('register_card');
        if (!array_key_exists($request->code_melli, $submittedIDs)
            // This "code_melli" hasn't been submitted
            or
            !array_key_exists('data', $submittedIDs[$request->code_melli])
            // This client didn't pass step 2 successfully
        ) {
            // Achieving this condition means that "code_melli" has been manipulated in read only mode by client
            return $this->jsonFeedback(null, [
                'ok'      => 0,
                'refresh' => 1,
            ]);
        }

        $checkResult = $this->checkCodeMelli($request->code_melli);
        if (!$checkResult['canRegister']) {
            return $checkResult['response'];
        }

        $data = $submittedIDs[$request->code_melli]['data'];
        $data['card_registered_at'] = Carbon::now()->toDateTimeString();

        $userId = User::store($data);


        if ($userId) {
            User::store(['id' => $userId, 'card_id' => $userId + 5000]);

            if(Role::findBySlug('card-holder')->exists) {
                $user = User::findBySlug($userId, 'id')->attachRole('card-holder');
            }

            Auth::loginUsingId($userId);
            $return = $this->jsonFeedback(null, [
                'redirect'     => url('members/my_card'),
                'ok'           => 1,
                'message'      => trans('forms.feed.register_success'),
                'redirectTime' => 2000,
            ]);
        } else {
            $return = $this->jsonFeedback(null, [
                'redirect'     => url('organ_donation_card'),
                'ok'           => 0,
                'message'      => trans('forms.feed.register_not_complete'),
                'redirectTime' => 2000,
            ]);
        }

//        $input = $request->toArray();
//        unset($input['_token']);
//        Session::forget('register_first_step');
//        $data = Session::pull('register_second_step');
//
//        if ($input['db-check'] != $data['code_melli']) {
//            return $this->jsonFeedback(null, [
//                'redirect' => url(''),
//            ]);
//        } else {
//            $data['card_registered_at'] = Carbon::now()->toDateTimeString();
////            $data['card_no'] = User::generateCardNo(); when register multi user in one time this line have bug
//        }

//        $user = User::selectBySlug($data['code_melli'], 'code_melli');
//        if ($user) {
//            $user_id = $user->id;
//            $data['id'] = $user->id;
//            $user_id = User::store($data);
//
//            if ($user_id) {
//                Auth::loginUsingId($user_id);
//                $return = $this->jsonFeedback(null, [
//                    'redirect'     => url('members/my_card'),
//                    'ok'           => 1,
//                    'message'      => trans('site.global.register_success'),
//                    'redirectTime' => 2000,
//                ]);
//            } else {
//                $return = $this->jsonFeedback(null, [
//                    'redirect'     => url('organ_donation_card'),
//                    'ok'           => 0,
//                    'message'      => trans('site.global.register_not_complete'),
//                    'redirectTime' => 2000,
//                ]);
//            }
//        } else {
//            $user_id = User::store($data);
//
//            if ($user_id) {
//                Auth::loginUsingId($user_id);
//                $return = $this->jsonFeedback(null, [
//                    'redirect'     => url('members/my_card'),
//                    'ok'           => 1,
//                    'message'      => trans('site.global.register_success'),
//                    'redirectTime' => 2000,
//                ]);
//            } else {
//                $return = $this->jsonFeedback(null, [
//                    'redirect'     => url('organ_donation_card'),
//                    'ok'           => 0,
//                    'message'      => trans('site.global.register_not_complete'),
//                    'redirectTime' => 2000,
//                ]);
//            }
//        }

        // generate card_no
//        if ($user_id and $user_id > 0) {
//            $update['id'] = $user_id;
//            $update['card_no'] = $user_id + 5000;
//            $user_id = User::store($update);
//        }

        return $return;
    }

    public function card_mini($national_hash)
    {
        ini_set("error_reporting", "E_ALL & ~E_NOTICE & ~E_STRICT");
        try {
            $national_hash = decrypt($national_hash);
        } catch (DecryptException $e) {
            return view('errors.404');
        }

        $user = User::selectBySlug($national_hash, 'code_melli');
        $user = $user->toArray();

        if ($user['card_status'] < 8) {
            return view('errors.403');
        }

        $font = public_path('assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'BNazanin.ttf');
        $enFont = public_path('assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'calibri.ttf');

        header("Content-type: image/png");
        header('Content-Disposition: filename=' . 'کارت_اهدای_عضو_' . $user['card_no'] . '.png');

        // orginal image
        $img = imagecreatefrompng(public_path('assets' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'cardMini.png'));

        // data
        $name_first = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');
        $name_father = FaGDServiceProvider::fagd($user['name_father'], 'fa', 'nastaligh');
        $birth_date = jDate::forge($user['birth_date'])->format('Y/m/d');
        $register_date = jDate::forge($user['card_registered_at'])->format('Y/m/d');

        // font size
        $font_size = 25;

        // position
        $name_position = imagettfbbox($font_size, 0, $font, $name_first);
        $name_position = $name_position[2] - $name_position[0];

        $name_father_position = imagettfbbox($font_size, 0, $font, $name_father);
        $name_father_position = $name_father_position[2] - $name_father_position[0];

        $card_no_position = imagettfbbox($font_size, 0, $font, $user['card_no']);
        $card_no_position = $card_no_position[2] - $card_no_position[0];

        $national_position = imagettfbbox($font_size, 0, $font, $user['code_melli']);
        $national_position = $national_position[2] - $national_position[0];

        $birth_date_position = imagettfbbox($font_size, 0, $font, $birth_date);
        $birth_date_position = $birth_date_position[2] - $birth_date_position[0];

        $register_date_position = imagettfbbox($font_size, 0, $font, $register_date);
        $register_date_position = $register_date_position[2] - $register_date_position[0];

        // Create some colors
        $black = imagecolorallocate($img, 0, 0, 0);

        // Add the text
        imagettftext($img, $font_size, 0, (500 - $card_no_position), 173, $black, $font, $user['card_no']);
        imagettftext($img, $font_size, 0, (500 - $name_position), 212, $black, $font, $name_first);
        imagettftext($img, $font_size, 0, (500 - $name_father_position), 254, $black, $font, $name_father);
        imagettftext($img, $font_size, 0, (500 - $national_position), 300, $black, $font, $user['code_melli']);
        imagettftext($img, $font_size, 0, (500 - $birth_date_position), 341, $black, $font, $birth_date);
        imagettftext($img, $font_size, 0, (500 - $register_date_position), 382, $black, $font, $register_date);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function card_full($national_hash, $mode = 'print')
    {
        ini_set("error_reporting", "E_ALL & ~E_NOTICE & ~E_STRICT");
        try {
            $national_hash = decrypt($national_hash);
        } catch (DecryptException $e) {
            return view('errors.404');
        }

        $user = User::selectBySlug($national_hash, 'code_melli');
        $user = $user->toArray();

        if ($user['card_status'] < 8) {
            return view('errors.403');
        }

        $font = public_path('assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'BNazanin.ttf');
        $enFont = public_path('assets' . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'php' . DIRECTORY_SEPARATOR . 'calibri.ttf');

        header("Content-type: image/png");
        if ($mode == 'print') {
            header('Content-Disposition: filename=' . 'کارت_اهدای_عضو_' . $user['card_no'] . '.png');
        } elseif ($mode == 'download') {
            header('Content-Description: File Transfer');
            header('Content-Type: image/png');
            header('Content-Disposition: attachment; filename="' . $user['card_no'] . '.png"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } else {
            header('Content-Disposition: filename=' . 'کارت_اهدای_عضو_' . $user['card_no'] . '.png');
        }

        // orginal image
        $img = imagecreatefrompng(public_path('assets' . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'finalCart.png'));

        // data
        $name_first = FaGDServiceProvider::fagd($user['name_first'] . ' ' . $user['name_last'], 'fa', 'nastaligh');
        $name_father = FaGDServiceProvider::fagd($user['name_father'], 'fa', 'nastaligh');
        $birth_date = jDate::forge($user['birth_date'])->format('Y/m/d');
        $register_date = jDate::forge($user['card_registered_at'])->format('Y/m/d');

        // font size
        $font_size = 30;

        // position
        $name_position = imagettfbbox($font_size, 0, $font, $name_first);
        $name_position = $name_position[2] - $name_position[0];

        $name_father_position = imagettfbbox($font_size, 0, $font, $name_father);
        $name_father_position = $name_father_position[2] - $name_father_position[0];

        $card_no_position = imagettfbbox($font_size, 0, $font, $user['card_no']);
        $card_no_position = $card_no_position[2] - $card_no_position[0];

        $national_position = imagettfbbox($font_size, 0, $font, $user['code_melli']);
        $national_position = $national_position[2] - $national_position[0];

        $birth_date_position = imagettfbbox($font_size, 0, $font, $birth_date);
        $birth_date_position = $birth_date_position[2] - $birth_date_position[0];

        $register_date_position = imagettfbbox($font_size, 0, $font, $register_date);
        $register_date_position = $register_date_position[2] - $register_date_position[0];

        $email_position = imagettfbbox(40, 0, $font, $user['email']);
        $email_position = $email_position[2] - $email_position[0];

        $mobile_position = imagettfbbox(40, 0, $enFont, $user['tel_mobile']);
        $mobile_position = $mobile_position[2] - $mobile_position[0];

        // Create some colors
        $black = imagecolorallocate($img, 0, 0, 0);

        // Add the text
        imagettftext($img, $font_size, 0, (850 - $card_no_position), 567, $black, $font, $user['card_no']);
        imagettftext($img, $font_size, 0, (850 - $name_position), 620, $black, $font, $name_first);
        imagettftext($img, $font_size, 0, (850 - $name_father_position), 665, $black, $font, $name_father);
        imagettftext($img, $font_size, 0, (850 - $national_position), 720, $black, $font, $user['code_melli']);
        imagettftext($img, $font_size, 0, (850 - $birth_date_position), 772, $black, $font, $birth_date);
        imagettftext($img, $font_size, 0, (850 - $register_date_position), 822, $black, $font, $register_date);
        imagettftext($img, 40, 0, (1850 - $mobile_position), 2115, $black, $font, $user['tel_mobile']);
        imagettftext($img, 40, 0, (1850 - $email_position), 2190, $black, $enFont, $user['email']);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($img);
        imagedestroy($img);
    }

    public function save_registration(Requests\CardRegisterRequest $request)
    {
        switch ($request->_step) {
            case 1:
                return $this->register_first_step($request);
                break;
            case 2:
                return $this->register_second_step($request);
                break;
            case 3:
                return $this->register_third_step($request);
                break;
        }
    }

    private function checkCodeMelli($codeMelli)
    {
        $user = User::findBySlug($codeMelli, 'code_melli');

        if ($user->exists) { // A user with the given "code_melli" exists.
            $loginLing = '<a href="' . route('login') . '">' . trans('front.messages.login') . '</a>';
            if ($user->is_admin()) { // This user is a volunteer
                $message = trans('front.messages.you_are_volunteer') . $loginLing;
                return [
                    'canRegister' => false,
                    'response'    => $this->jsonFeedback(null, [
                        'ok'      => true,
                        'message' => $message,
                    ]),
                ];
            } else if ($user->withDisabled()->is_admin()) { // This user id a blocked volunteer
                return [
                    'canRegister' => false,
                    'response'    => $this->jsonFeedback(null, [
                        'ok'      => false,
                        'message' => trans('front.messages.unable_to_register_card'),
                    ]),
                ];
            } else if ($user->is_an('card-holder')) { // This user has card
                $message = trans('front.messages.you_are_card_holder') . $loginLing;
                return [
                    'canRegister' => false,
                    'response'    => $this->jsonFeedback(null, [
                        'ok'      => true,
                        'message' => $message,
                    ]),
                ];
            }
        }

        return ['canRegister' => true];
    }
}
