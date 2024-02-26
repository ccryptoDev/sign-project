<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

use App\Models\User;

use App\Notifications\ResetPassword;

class AuthController extends Controller
{
    public function login_view(Request $request) {
        if (Auth::check()) {
            return redirect()->intended('sign-editor');
            return redirect()->back();
        }
        return view('auth.login');
    }

    public function register_view(Request $request) {
        return view('auth.register');
    }

    public function forgot_password(Request $request) {
        return view('auth.forgot-password');
    }
    
    public function login_request(Request $request) {
        $messages = [
            "email.required" => "Email is required",
            "password.required" => "Password is required",
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $credentials = $request->only('email', 'password');
        if (Auth::guard('user')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = User::where('email', $request->email)->first();
            Auth::login($user);
            return redirect()->intended('sign-editor');
        } else if (Auth::guard('admin')->attempt($credentials)){
            $request->session()->regenerate();
            $user = User::where('email', $request->email)->first();
            Auth::login($user);
            return redirect()->intended('sign-editor');
        } else if (Auth::guard('super')->attempt($credentials)){
            $request->session()->regenerate();
            $user = User::where('email', $request->email)->first();
            Auth::login($user);
            return redirect()->intended('sign-editor');
            return redirect()->back();
            return redirect()->route('dashboard');
        }
        else{
            return back()->withInput()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    public function register_request(Request $request) {
        $messages = [
            "email.required" => "Email is required",
            "password.required" => "Password is required",
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    }


    public function forgot_password_request(Request $request) {
        $messages = [
            "email.required" => "Email is required"
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $status = Password::sendResetLink(
            $request->only('email')
        );
     
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);

    }

    public function reset_password(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
