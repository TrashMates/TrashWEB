<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
