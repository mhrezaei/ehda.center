<?php

namespace App\Http\Middleware;

use App\Http\Requests\Request;
use App\Traits\GlobalControllerTrait;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class DetectLanguage
{
    use GlobalControllerTrait;
    public function handle($request, Closure $next)
    {
        $lang = $request->segment(1);

        if ($lang == 'fa' or $lang == 'en' or $lang == 'ar')
        {
            \App::setLocale($lang);
        }
        else
        {
            \App::setLocale('fa');
        }

        return $next($request);
    }
}
