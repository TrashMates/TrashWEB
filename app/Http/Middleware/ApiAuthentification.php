<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuthentification
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if (!$request->header("token") && !$request->has("token")) {
			return response(["error" => "401 - You need to specify the token header to continue."], 401);
		} elseif ($request->header("token") != env("API_TOKEN") && $request->get("token") != env("API_TOKEN")) {
			return response(["error" => "401 - The provided token is invalid."], 401);
		}

		return $next($request);
	}
}
