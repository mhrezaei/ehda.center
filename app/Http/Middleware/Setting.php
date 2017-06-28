<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Setting
{
    public function handle($request, Closure $next)
    {
        $suspend_site = !getSetting('overall_activeness');
        if ($suspend_site)
        {
            if (!user()->is_admin())
            {
                return new Response(view('setting.under_construction'));
            }
        }
        return $next($request);
    }
}
