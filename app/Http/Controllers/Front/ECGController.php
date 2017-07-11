<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ECGController extends Controller
{
    public function copy()
    {
        return view('front.ecg.main');
    }
}
