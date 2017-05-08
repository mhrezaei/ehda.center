<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\ProfileSaveRequest;
use App\Models\Post;
use App\Models\Receipt;
use App\Models\User;
use App\Providers\DrawingCodeServiceProvider;
use App\Providers\PostsServiceProvider;
use App\Traits\ManageControllerTrait;
use Asanak\Sms\Facade\AsanakSms;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Facades\jDateTime;

class UserController extends Controller
{
    use ManageControllerTrait;

    public function index(Request $request)
    {
        if ($request->session()->get('drawingCode'))
            return redirect(url_locale('user/drawing'));

//        $post = Post::findBySlug('customers-comments');

        return view('front.user.dashboard.0', compact('post'));
    }

    public function profile()
    {
        user()->spreadMeta();
//        if (user()->birth_date) {
//            user()->birth_date = jDateTime::strftime('Y/m/d', strtotime(user()->birth_date));;
//        }

//        if (user()->marriage_date) {
//            user()->marriage_date = jDateTime::strftime('Y/m/d', strtotime(user()->marriage_date));;
//        }
        return view('front.user.profile.0');
    }

    public function drawing(Request $request)
    {
        $receipt = $request->session()->get('drawingCode');
        if ($receipt) {
            $receipt = decrypt($receipt);
            $drawing_code = DrawingCodeServiceProvider::check_uniq($receipt);
            if (!$drawing_code)
                $request->session()->forget('drawingCode');

            $exists = Receipt::findBySlug($receipt, 'code')->first();
            if ($exists)
                $request->session()->forget('drawingCode');

            $new_receipt = [
                'user_id' => user()->id,
                'code' => $receipt,
                'purchased_at' => Carbon::createFromTimestamp($drawing_code['date'], 'Asia/Tehran')->setTimezone('UTC'),
                'purchased_amount' => $drawing_code['price'],
            ];
            Receipt::store($new_receipt);

            if (setting()->ask('send_sms_on_register_code')->gain()) {
                $smsText = str_replace('::name', user()->name_first, trans('front.register_code_success_sms'))
                    . "\n\r"
                    . setting()->ask('site_url')->gain();

                $sendingResult = AsanakSms::send(user()->mobile, $smsText);
                $sendingResult = json_decode($sendingResult);
            }

            $request->session()->forget('drawingCode');
            $request->session()->forget('drawing_try');
        }
        return view('front.user.drawing.0');
    }

    public function events()
    {
        $runningEvents = Post::selector(['type' => 'events'])
            ->whereDate('starts_at', '<=', Carbon::now())
            ->whereDate('ends_at', '>=', Carbon::now())
            ->get();

        return view('front.user.events.0', compact('runningEvents'));

    }

    public function waitingEvents()
    {
        if (request()->ajax()) {
            $events = Post::selector(['type' => 'events'])
                ->whereDate('starts_at', '>=', Carbon::now())
                ->whereDate('ends_at', '>=', Carbon::now())
                ->get();

            return view('front.user.events.events-block', compact('events'));
        }
    }

    public function expiredEvents()
    {
        if (request()->ajax()) {
            $events = Post::selector(['type' => 'events'])
                ->whereDate('starts_at', '<=', Carbon::now())
                ->whereDate('ends_at', '<=', Carbon::now())
                ->paginate(5);

            return view('front.user.events.events-block', compact('events'));
        }
    }

    public function update(ProfileSaveRequest $request)
    {
        $request = $request->all();
        if ($request['new_password']) {
            $request['password'] = Hash::make($request['new_password']);
        }
        $request['id'] = user()->id;
        if ($request['marital'] != 2) {
            unset($request['marriage_date']);
        }
        return $this->jsonAjaxSaveFeedback(User::store($request, ['new_password', 'new_password2']), [
            'success_refresh' => 1,
        ]);
    }
}
