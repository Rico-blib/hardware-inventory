<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        // Get the first (and only) settings row
        $setting = Setting::first();

        return view('settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'contact_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'login_background_color' => 'nullable|string|max:20',
            'login_background_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $setting = Setting::firstOrCreate(['id' => 1]);

        $setting->app_name = $request->app_name;
        $setting->contact_number = $request->contact_number;
        $setting->email = $request->email;
        $setting->login_background_color = $request->login_background_color;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $setting->logo_path = $logoPath;
        }

        if ($request->hasFile('login_background_image')) {
            $bgPath = $request->file('login_background_image')->store('backgrounds', 'public');
            $setting->login_background_image = $bgPath;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
