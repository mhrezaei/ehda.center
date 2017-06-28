<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('errors.404');
    }

    public function ramazan()
    {
        $count = User::where('created_by', '303958')->count();
        return view('front.landing.events.ramazan.0', compact('count'));
    }

    public function ramazan_count()
    {
        $data['count'] = User::where('created_by', '303958')->count();
        $data['status'] = 1;
        $data['sm'] = csrf_token();
        return json_encode($data);
    }

    public function summer()
    {
        $count = User::where('created_by', '303958')
            ->where('card_registered_at' , '>' , '2017-06-22 00:00:00' )
            ->where('card_registered_at' , '<' , '2017-09-22 23:59:59' )
            ->count();
        return view('front.landing.events.summer.0', compact('count'));
    }

    public function summer_count()
    {
        $data['count'] = User::where('created_by', '303958')
            ->where('card_registered_at' , '>' , '2017-06-22 00:00:00' )
            ->where('card_registered_at' , '<' , '2017-09-22 23:59:59' )
            ->count();
        $data['status'] = 1;
        $data['sm'] = csrf_token();
        return json_encode($data);
    }
}
