<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function dashboard(Request $request) {
        return view('user.profile.profile');
    }

    public function view_profile(Request $request) {
        return view('user.profile.profile');
    }

    public function update_profile(Request $request) {
        $messages = [
            "first_name.required" => "First Name is required",
            "last_name.required" => "Last Name is required",
            "email.required" => "Email is required",
        ];
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->business_name = $request->business_name;
        $user->save();
        // return Auth::
        return back()->withSuccess("Success");
    }

    public function change_password(Request $request) {
        return view('user.profile.change-password');
    }

    public function update_password(Request $request) {
        $messages = [
            'current_password' => 'required',
            'password' => 'min:6|confirmed|required_with:password_confirmation|same:password_confirmation',
        ];
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!\Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
            'password' => 'min:6|confirmed|required_with:password_confirmation|same:password_confirmation',
        ], $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user->forceFill([
            'password' => Hash::make($request->password)
        ])->setRememberToken(Str::random(60));
        $user->save();
        // return Auth::
        return back()->withSuccess("Success");
    }
}