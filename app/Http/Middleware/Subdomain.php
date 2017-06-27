<?php

namespace App\Http\Middleware;

use App\Models\Domain;
use Closure;
use Illuminate\Support\Facades\Session;

class Subdomain
{
    public function handle($request, Closure $next)
    {
        $subdomain = $this->detectSubDomain();
        if ($subdomain == 'inspector')
        {
            return redirect(getSetting('site_url') . 'inspector');
        }
        elseif ($subdomain == 'global')
        {
            Session::put('domain', 'global');
        }
        else
        {
            $domain = Domain::where('alias', $subdomain)->first();
            if ($domain)
            {
                setDomain($domain->slug);
            }
            else
            {
                setDomain();
                // @TODO: remove bottom line from comment in production version
//                return redirect(getSetting('site_url'));
            }
        }
        return $next($request);
    }

    public function detectSubDomain()
    {
        $subdomain = str_replace('http://', '', url(''));
        $subdomain = str_replace('https://', '', $subdomain);
        $subdomain = explode('.', $subdomain);
        if ($subdomain[0] == 'www')
        {
            if ($subdomain[1] == 'ehda')
            {
                $subdomain = 'global';
            }
            else
            {
                $subdomain = $subdomain[1];
            }
        }
        else
        {
            if ($subdomain[0] == 'ehda')
            {
                $subdomain = 'global';
            }
            else
            {
                $subdomain = $subdomain[0];
            }
        }

        return $subdomain;
    }
}
