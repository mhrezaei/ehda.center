<?php

namespace App\Http\Middleware;

use App\Providers\SettingServiceProvider;
use App\Traits\GlobalControllerTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Setting
{
    use GlobalControllerTrait;
    // @TODO fix it hadi
    public function handle($request, Closure $next)
    {
//        $suspend_site = SettingServiceProvider::get('suspend_site');
//        if (!$suspend_site)
//        {
//            if (!Auth::check() or ! Auth::user()->isAdmin())
//            {
//                return new Response(view('setting.under_construction'));
//            }
//        }
        return $next($request);
    }
}
