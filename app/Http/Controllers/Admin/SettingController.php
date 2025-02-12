<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {

        $setting = Setting::where('user_id', Auth::user()->user_id)->first();

        return view("admin.settings", compact('setting'));
    }

    public function changeTheme(Request $request)
    {
        $user_id = Auth::user()->user_id;
        $setting = Setting::where('user_id', $user_id)->first();
        
        $theme = $request->input('theme');
        $setting->is_theme_dark = $theme === 'dark';
        $setting->save();
        
        return response()->json(['success' => true]);
    }
}
