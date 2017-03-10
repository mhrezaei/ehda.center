<?php

namespace App\Http\Controllers\Front;

use App\Models\Receipt;
use App\Providers\DrawingCodeServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->get('drawingCode'))
            return redirect(url_locale('user/drawing'));
        return view('front.user.dashboard.0');
    }

    public function profile()
    {
        return view('front.user.profile.0');
    }

    public function drawing(Request $request)
    {
        $receipt = $request->session()->get('drawingCode');
        if ($receipt)
        {
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
            $request->session()->forget('drawingCode');
            $request->session()->forget('drawing_try');
        }
        return view('front.user.drawing.0');
    }
}
