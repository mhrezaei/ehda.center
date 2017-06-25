<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;


class IsMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @param                           $requested_role
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next, $requested_role)
	{
		if($requested_role=='admin') {
			$condition = user()->is_admin() ;
		}
		elseif($requested_role=='developer') {
			$condition = user()->isDeveloper() ;
		}
		else {
			$condition = user()->hasRole($requested_role) ;
		}

		if(!$condition) {
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
