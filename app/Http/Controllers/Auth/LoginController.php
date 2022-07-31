<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Hash;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $login = [
            $loginType => $request->phone,
            'password' => $request->password
        ];

        if (auth()->attempt($login)) {
            if (auth()->user()->is_verifikasi == 1) {
                return redirect('dashboard');
            }else{
                Auth::logout();

                return redirect()->route('login')->with('error','Mohon Maaf, akun Anda belum diverifikasi');
            }
            return redirect()->intended('home');
        }
        return redirect()->back()->with(['error' => 'No. HP/Email/Kata Sandi salah!']);
    }
}
