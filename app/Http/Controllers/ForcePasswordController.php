<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\Project;
use App\Role;
use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterCustomerAdminAccount;
use App\Mail\RegisterCustomerUserAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class ForcePasswordController extends Controller
{

	public function index(Request $request)
	{
		return view('auth.forcepassword');
	}

	public function updatePassword(Request $request)
	{
		$request->validate([
			'old_password'=> 'required|check_password',
            'password' => 'required|string|min:8|max:12|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,12}$/',
		], [
			'old_password.check_password' => 'Your old password doesnt match. ',
			'password.regex' => 'Your password has capital, number, min 8 and max 12 character'
		]);

		$user = Auth::user();
		$user->password = bcrypt($request->password);
		$user->password_change_at = Carbon::now();
		$user->save();
		
		return redirect('home')->with('success','Thank you for change your password.');
	}


}