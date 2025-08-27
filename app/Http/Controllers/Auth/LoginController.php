<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\LoginAttempt;
use App\User;
use App\LastLogger;
use Illuminate\Support\Facades\Mail;
use App\Mail\UnlockAccount;

use Illuminate\Support\Facades\Auth;
use Validator;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {

        try {
            LastLogger::create([
                'user_id' => $user->id,
                'responses' => $user,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        } catch (\Exception $e) {

        }

        $token = $user->remember_token;
        $id = $user->user_id;

        // Update login status
        // if(!$user->hasRole('Superadmin')) {
        //     $user->is_logged_in = 1;
        //     $user->save();
        // }

        // Clear previous failed login attempts
        LoginAttempt::where([
            'user_id' => $user->user_id,
        ])->delete();

        // Redirect according to roles
        if ( $user->hasRole('Superadmin') ) {
            return redirect('/companies');
        }
        if ( $user->hasRole('Admin') || $user->hasRole('Admin-BGR') ) {
            return redirect('/projects');
        }
        if ( $user->hasRole('CargoOwner') ) {
            return redirect('/advance_notices/inbound');
        }
        if ($user->hasRole('CommandCenter')) {
            return redirect('/projects');
        }
        if ($user->hasRole('WarehouseChecker') || $user->hasRole('WarehouseOfficer')) {
            return redirect('/stock_transports/inbound');
        }
        if ($user->hasRole('Admin-Client')) {
            return redirect('/warehouses');
        }
        if ($user->hasRole('SPI')) {
            return redirect('/warehouses');
        }
        if($user->hasRole('Reporting')) {
            return redirect('/report/management_stock_report');
        }
        if ($user->hasRole('Transporter')) {
            return redirect('/deliveries');
        }
        return redirect('/advance_notices/inbound');
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ]);
    }

    public function username()
    {
      return 'user_id';
    }

    public function login(Request $request)
    {
        $request->validate([
            'captcha' => 'required|captcha'
        ], [
            'captcha.captcha' => 'Wrong captcha please try again.'
        ]);

        $checkAttempts = LoginAttempt::where([
            'user_id' => $request->user_id,
            'is_active' => 1
        ])->count();
        
        $payloadMessage[$this->username()] =  [trans('auth.failed')];

        if ($checkAttempts >= 3) {
            User::where('user_id', $request->user_id)->update(['is_locked' => 1]);

            $payloadMessage['locked_account'] = 'Akun Anda terkunci karena terlalu banyak percobaan login yang gagal. Harap mengajukan pembukaan Akun untuk membuka kembali Akun Anda.';
            $payloadMessage['user_id'] = '';
            throw ValidationException::withMessages($payloadMessage);
        }

        // Check apakah user yang sama sudah login
        $is_logged_in = User::where('user_id', $request->user_id)->where('is_logged_in',1)->first();

        if ($is_logged_in) {
            $payloadMessage['locked_account'] = 'User yang sama sudah login di sistem';
            $payloadMessage['user_id'] = '';
            throw ValidationException::withMessages($payloadMessage);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        if (strtolower($request->user_id) != 'superadmin') {
            if (User::where('user_id', $request->user_id)->count() > 0) {
                LoginAttempt::create([
                    'user_id' => $request->user_id,
                    'ip_address' => \Request::getClientIp()
                ]);
            }
        }

        $payloadMessage[$this->username()] =  [trans('auth.failed')];
        throw ValidationException::withMessages($payloadMessage);
    }

    public function unlock() {
        return view('auth.unlock');
    }

    public function unlock_post(Request $request) {

        //Validate
        $request->validate([
            'user_id' => 'required'
        ]);

        $user = User::where('user_id', $request->user_id)->first();
        if (empty($user)) {
            return redirect('unlock_account')->with('message', 'User ID tidak ditemukan.');
        }

        if($user->is_locked == 1) {
            $user->has_unlock_requested = 1;
            $user->save();
            
            Mail::to($user->email)->queue(new UnlockAccount($user));
            return redirect('login')->with('message', 'Pengajuan pembukaan Akun berhasil dikirim, harap menunggu notifikasi berikutnya di E-mail Anda.');
        } else {
            return redirect('login')->with('message', 'Silahkan login kembali.');
        }
    }

    public function unlock_verification($id){
        $user = User::find($id);
        $user->has_unlock_request_verified = 1;
        $user->save();

        return view('auth.unlock_verified');
    }

    public function logout(Request $request)
    {
        // Update login status
        // $user = Auth::user();
        // $user->is_logged_in = 0;
        // $user->save();

        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}
