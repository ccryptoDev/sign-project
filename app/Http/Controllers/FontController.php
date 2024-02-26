<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Fonts;

class FontController extends Controller
{
    public function view_font_editor(Request $request) {
        $level = Auth::user()->level;
        if ($level != 2) {
            return back()->withErrors(['msg' => "You don't have permission to access this page"]);
        }
        return view('user.font.create');
    }

    public function save_font(Request $request) {
        $exist = Fonts::where('letter', $request['letter'])->first();
        if(isset($exist->id)) {
            Fonts::where('letter', $request['letter'])
                ->update([
                    'width' => $request['width'],
                    'height' => $request['height'],
                    'font' => $request['font'],
                    'ascii' => $request['ascii']
                ]);
            return 'here';
        } else {
            $fonts = new Fonts;
            $fonts->letter = $request['letter'];
            $fonts->width = $request['width'];
            $fonts->height = $request['height'];
            $fonts->ascii = $request['ascii'];
            $fonts->font = $request['font'];
            $fonts->save();
        }
        return 'success';
    }

    public function font_lists(Request $request) {
        $fonts = Fonts::orderBy('id', 'DESC')->get();
        return $fonts;
    }

    public function upload_font(Request $request) {
        $file = $request->file("file");
        if(isset($file)){
            $file_name = str_replace(" ","-",$file->getClientOriginalName());
            $rand = rand(10,1000);
            $rand = md5($rand);
            $name = $rand.$file_name;
            $file->move("fonts/",$name);
            $result = [];
            $result['success'] = true;
            $result['path'] = $name;
            return $result;
        }
        return $request;
    }
}
