<?php

namespace App\Http\Controllers\Front;

use App\Models\Domain;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatesController extends Controller
{
    public function map()
    {
        $states = Domain::all();

        return view('front.iranmap.main', compact('states'));
    }
}
