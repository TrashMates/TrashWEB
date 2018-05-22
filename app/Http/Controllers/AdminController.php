<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

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
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function login(Request $request)
	{
		$username = $request->input("username");
		$password = hash("sha256", $username . "-" . $request->input("password"));

		$User = User::select(["id", "username"])
			->where("username", "=", $username)
			->where("password", "=", $password)
			->first();

		if ($User) {
			Session::put("User", $User);
			return redirect(route("admin.index"));
		}

		return redirect(route("admin.login"));
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view("admin.index");
	}
}
