<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\DrawingCodeRequest;
use App\Models\Receipt;
use App\Providers\DrawingCodeServiceProvider;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DrawingCodeController extends Controller
{
    public function index()
    {
        return view('errors.404');
    }

    public function sumbitCode(DrawingCodeRequest $request)
    {
        $input = $request->toArray();
        $code = $input['code1'] . $input['code2'] . $input['code3'] . $input['code4'];
        $data = array();
        $continue = true;
        $count = 1;
        
        if ($request->session()->get('drawing_try'))
        {
            $try = $request->session()->get('drawing_try');
            $count = $try['count'];

            if ($count > 100)
            {
                $continue = false;
            }

            $data['status'] = 'fail';
            $data['msg'] = trans('front.drawing_check_code_fail');
            $data['abc'] = 1;
        }
        else
        {
            $request->session()->put(
                'drawing_try' ,
                [
                    'count' => $count,
                    'last' => Carbon::now()->toDateTimeString(),
                ]);
        }

        if (DrawingCodeServiceProvider::check_uniq($code) && $continue)
        {
            $check_drawing = Receipt::where('code', $code)->get();
            if (sizeof($check_drawing))
            {
                $data['status'] = 'fail';
                $data['msg'] = trans('front.drawing_check_code_fail');
                $data['abc'] = 2;

                $try = $request->session()->get('drawing_try');
                $try['count'] = $try['count'] + 1;
                $request->session()->put('drawing_try', $try);
            }
            else
            {
                $request->session()->put('drawingCode', encrypt($code));
                $data['status'] = 'success';
                $data['msg'] = trans('front.drawing_code_success_receive_please_wait');
                $data['login'] = user()->exists;
            }
        }
        else
        {
            $try = $request->session()->get('drawing_try');
            $try['count'] = $try['count'] + 1;
            $request->session()->put('drawing_try', $try);

            $data['status'] = 'fail';
            $data['msg'] = trans('front.drawing_check_code_fail');
            $data['abc'] = 3;
        }

        echo json_encode($data);
    }
}
