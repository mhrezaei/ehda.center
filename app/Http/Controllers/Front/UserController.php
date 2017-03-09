<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('front.user.dashboard.0');
    }

    public function profile()
    {
        return view('front.user.profile.0');
    }
}
