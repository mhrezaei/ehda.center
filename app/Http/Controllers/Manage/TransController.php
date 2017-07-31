<?php

namespace App\Http\Controllers\Manage;

use App\Providers\TransServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransController extends Controller
{
    /**
     * Shows array of ids that is not in all available languages
     */
    public function diff()
    {
        $diff = TransServiceProvider::getAllDifferences();
        dd($diff);
    }
}
