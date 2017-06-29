<?php

namespace App\Http\Middleware;

use App\Http\Requests\Request;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class DetectLanguage
{
    public function handle($request, Closure $next)
    {
        $current_lang = $request->segment(1);
        $auto_set_lang = getSetting('site_lang_auto_detect');
        $allowed_lang = getSetting('site_locales');
        $user_ip = $request->ip();

        if ($user_ip != '::1' and $auto_set_lang)
        {
            $data = file_get_contents('http://freegeoip.net/json/' . $user_ip);
            if ($data)
            {
                $data = json_decode($data, true);
                if ($data['country_code'] == 'IR')
                {
                    $this->setDetectedLang('fa');
                }
                else
                {
                    $this->setDetectedLang('en');
                }
            }
            else
            {
                $this->setDetectedLang('fa'); // @TODO: set site default lang
            }
        }
        else
        {
            if (in_array($current_lang, $allowed_lang))
            {
                $this->setDetectedLang($current_lang);
            }
            else
            {
                $this->setDetectedLang('fa'); // @TODO: set site default lang
            }
        }

        return $next($request);
    }

    public function setDetectedLang($lang)
    {
        \App::setLocale($lang);
    }
}
