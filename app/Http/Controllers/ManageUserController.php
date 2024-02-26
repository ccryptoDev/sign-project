<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ManageUserController extends Controller
{
    public function view_management(Request $request) {
        $users = User::get();
        $data['users'] = $users;
        $level = Auth::user()->level;
        if($level != 1 && $level != 2) {
            return back()->withErrors(['msg' => "You don't have permission to access this page"]);
        }
        return view('user.user-management.list', $data);
    }

    public function list_users(Request $request) {
        $level = Auth::user()->level;
        $id = Auth::user()->id;
        $users = User::where('id', '!=', $id)->get();
        if($level == 1) {
            $users = User::where('id', '!=', $id)
                ->whereIn('level', [0, 1])
                ->get();
        }
        foreach ($users as $key => $value) {
            $value->key = $key + 1;
            $value->time = date_format($value->created_at, "Y-m-d H:i:s");
        }
        $data = [];
        $data['data'] = $users;
        $data['recordsTotal'] = count($users);
        $data['recordsFiltered'] = count($users);
        return $data;
    }

    public function create_new_user(Request $request) {
        $level = Auth::user()->level;
        if ($level == 0) {
            return "You don't have permission to create a user";
        }
        if ($level == 1 && $request->level == 2) {
            return "You don't have permission to create a Super User";
        }
        $exist = User::where('email', $request->email)->first();
        if (isset($exist->id)) {
            return "This email address already exists in our system. Please use another email.";
        }
        $user = new User;
        $user->name = $request->first_name ." ". $request->last_name;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = Hash::make('userpassword');
        $user->email = $request->email;
        $user->level = $request->level;
        $user->save();
        return 'Success';
    }

    public function update_user(Request $request) {
        $level = Auth::user()->level;
        if ($level == 0) {
            return "You don't have permission to create a user";
        }
        if ($level == 1 && $request->level == 2) {
            return "You don't have permission to create a Super User";
        }
        $exist = User::where('email', $request->email)
            ->where('id', '!=', $request->id)
            ->first();
        if (isset($exist->id)) {
            return "This email address already exists in our system. Please use another email.";
        }
        User::where('id', $request->id)
            ->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'level' => $request->level
            ]);
        return 'Success';
    }

    public function delete_user(Request $request) {
        $level = Auth::user()->level;
        if ($level == 0) {
            return "You don't have permission to create a user";
        }
        User::where('id', $request->id)->delete();
        return 'Success';
    }
}
