<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignController extends Controller
{
    public function sign_editor(Request $request) {
        return view('user.sign.editor');
    }
}
