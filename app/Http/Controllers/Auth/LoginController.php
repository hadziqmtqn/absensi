<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
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

    public function index()
    {
        $appName = Setting::first();

        return view('auth.login', compact('appName'));
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $karyawan = Auth::user()->modelHasRole->role_id == 2;
            $karyawanTerverifikasi = auth()->user()->is_verifikasi == 1;

            if ($karyawan) {
                if($karyawanTerverifikasi){
                    return redirect('dashboard');
                }else{
                    Auth::logout();

                    return redirect()->route('login')->with('error','Mohon Maaf, akun Anda belum diverifikasi');
                }
            }

            $user = User::where('email', $request['email'])->firstOrFail();
            $user->createToken('auth_token', ['*'], now()->addRealHours(8))->plainTextToken;

            return redirect()->intended('home');
        }

        return redirect()->back()->with(['error' => 'No. HP/Email/Kata Sandi salah!']);
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            Alert::success('Success', 'Anda Berhasil Keluar');

            return redirect('login');
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            Alert::error('Oops', 'Anda Gagal Keluar');

            return back();
        }
    }
}
