<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\RegisterSaveRequest;
use App\Models\Comment;
use App\Models\Folder;
use App\Models\Post;
use App\Models\User;
use App\Providers\EmailServiceProvider;
use App\Providers\PostsServiceProvider;
use App\Traits\TahaControllerTrait;
use Asanak\Sms\Facade\AsanakSms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class FrontController extends Controller
{
    use TahaControllerTrait;

    public function index()
    {
        $mainSlideShow = Post::selector([
            'type'     => 'slideshows',
            'category' => 'main-slideshow',
            'domain'   => getUsableDomains(),
        ])->get();

        $deadlinesHTML = PostsServiceProvider::showList([
            'type'         => 'deadlines',
            'max_per_page' => -1,
            'sort'         => 'asc',
        ]);

        return view('front.home.main', compact(
            'deadlinesHTML',
            'mainSlideShow'
        ));
    }

    public function register(RegisterSaveRequest $request)
    {
        $input = $request->toArray();

        // check user exists
        $user = User::where('code_melli', $input['code_melli'])->first();
        if ($user) {
            if ($user->is_a('customer')) {
                return $this->jsonFeedback(null, [
                    'ok'      => 1,
                    'message' => trans('front.relogin'),
                ]);
            } else {
                return $this->jsonFeedback(null, [
                    'ok'      => 1,
                    'message' => trans('front.code_melli_already_exists'),
                ]);
            }
        }

        // store user to database
        $user = [
            'code_melli' => $input['code_melli'],
            'mobile'     => $input['mobile'],
            'name_first' => $input['name_first'],
            'name_last'  => $input['name_last'],
            'password'   => Hash::make($input['password']),

        ];

        if ($input['email']) {
            $user['email'] = $input['email'];
        }

        $store = User::store($user);

        if ($store) {
            // login user
            Auth::loginUsingId($store);

            // add customer role
            user()->attachRole('customer');

            // send sms
            if (setting()->ask('send_sms_on_register_user')->gain()) {
                $smsText = str_replace('::site', setting()->ask('site_title')->gain(), trans('front.register_success_sms'))
                    . "\n\r"
                    . setting()->ask('site_url')->gain();

                $sendingResult = AsanakSms::send($user['mobile'], $smsText);
                $sendingResult = json_decode($sendingResult);
            }

            if (isset($user['email']) and setting()->ask('send_email_on_register_user')->gain()) {
                $emailText = str_replace('::site', setting()->ask('site_title')->gain(), trans('passwords.register_success_email'))
                    . "\n\r"
                    . setting()->ask('site_url')->gain();

                $sendingResult = EmailServiceProvider::send($emailText, $user['email'], trans('front.site_title'), trans('people.form.recover_password'), 'default_email');
            }


            return $this->jsonFeedback(null, [
                'ok'       => 1,
                'message'  => trans('front.register_success'),
                'redirect' => url_locale('user/dashboard'),
            ]);
        } else {
            return $this->jsonFeedback(null, [
                'ok'      => 0,
                'message' => trans('front.register_failed'),
            ]);
        }


    }

    public function heyCheck()
    {
        return $this->jsonFeedback([
            'ok' => user()->exists,
        ]);
        //return 12 ;

    }

    public function testCart()
    {
        $products = Post::selector(['type' => 'products', 'locale' => 'fa'])
            ->orderBy('published_at', 'DESC')
            ->limit(5)
            ->get();

        $cart = new \stdClass();
        $cart->items = [];
        $sum = 0;

        foreach ($products as $key => $product) {
            $tmp = new \stdClass();
            $tmp->product = $product;
            $tmp->count = $key + 5;
            $cart->items[$key] = $tmp;
            $sum += $product->price;
            if (!isset($mostExpensive) or $mostExpensive->price < $product->price) {
                $mostExpensive = $product;
            }
        }

        if ($sum) {
            $cart->sum = $sum;
            $cart->discount = $sum * 0.2;
        }

        return view('front.cart.main', compact('cart', 'mostExpensive'));
    }
}
