<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ECGController extends Controller
{
    public function copy()
    {
        return view('front.ecg.copy.main');
    }

    public function simulator()
    {
        return view('front.ecg.simulator.main');
    }

    public function simulator_dev()
    {
        return view('front.ecg.simulator.main', ['dev' => true]);
    }
}
