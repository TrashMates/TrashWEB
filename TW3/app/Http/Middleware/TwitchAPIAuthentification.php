<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class TwitchAPIAuthentification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (Session::has("twitch_code")) {
            return $next($request);
	    }

	    Session::put("previous_route", $request->route()->getName());
	    return redirect("https://id.twitch.tv/oauth2/authorize?client_id=" . env("TWITCH_CLIENTID") . "&redirect_uri=" . env("TWITCH_REDIRECT_URL") . "&response_type=code&force_verify=true");
    }
}
