<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;


class CanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $requested_permission
     * @param  $requested_role: default: 'admin'
     * @return mixed
     */
    public function handle($request, Closure $next , $requested_permission , $requested_role = 'admin')
    {
        if(!user()->as($requested_role)->can($requested_permission)) {
            if($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }
            else {
                return new Response(view('errors.403'));
            }
        }
        return $next($request);
    }
}
