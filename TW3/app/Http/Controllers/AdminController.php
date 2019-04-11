<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;

class AdminController extends Controller
{

	/**
	 * GET - Display the Login Form
	 */
	public function loginForm()
	{
		return view("admin.login");
	}

	/**
	 * POST - Log in the User
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function login(Request $request)
	{
		$username = $request->input("username");

		$User = User::where("username", "=", $username)->first();
		if ($User && Hash::check($request->input('password'), $User->password)) {
			Session::put("User", $User);
			return redirect(route("admin.index"));
		}

		return redirect(route("admin.login"));
	}

	public function logoff(Request $request)
	{
		Session::flush();

		return redirect(route("admin.login"));
	}

	public function oauth(Request $request)
	{
		if (Session::has("previous_route")) {
			if ($request->has("code")) {
				$twitch = json_decode(Curl::to("https://id.twitch.tv/oauth2/token?client_id=" . env("TWITCH_CLIENTID") . "&client_secret=" . env("TWITCH_CLIENT_SECRET") . "&code=" . $request->get('code') . "&grant_type=authorization_code&redirect_uri=" . env("TWITCH_REDIRECT_URL"))
					->post());
				Session::put("twitch_code", $twitch->access_token);
			}

			$route = Session::get("previous_route") ?? "admin.index";
			Session::remove("previous_route");

			return redirect(route($route));
		}
	}

	/**
	 * GET - Display the Admin Index Page
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view("admin.index");
	}
}
