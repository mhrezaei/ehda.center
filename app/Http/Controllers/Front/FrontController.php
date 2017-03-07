<?php

namespace App\Http\Controllers\Front;

use App\Http\Requests\Front\RegisterSaveRequest;
use App\Traits\TahaControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FrontController extends Controller
{
    use TahaControllerTrait;
    public function index()
    {
        return view('front.home.0');
    }

    public function register(RegisterSaveRequest $request)
    {
        $input = $request->toArray();
        return $this->jsonFeedback(null, [
            'ok' => 1,
            'message' => trans('forms.feed.wait'),
        ]);
    }
}
