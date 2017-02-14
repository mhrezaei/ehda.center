<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class IsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $requested_role
     * @return mixed
     */
    public function handle($request, Closure $next , $requested_role)
    {
        if(!user()->hasRole($requested_role)) {
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
