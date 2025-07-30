<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class ActivationController extends Controller
{
    public function showForm()
    {
        return view('activation');
    }

    public function activate(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
        ]);

        // 🧠 You can enhance this: validate key via hash, signature, etc.
        $validKey = 'CUPA-TECH-HUB'; // You define your key system

        if ($request->key === $validKey) {
            $setting = Setting::first();
            $setting->activated = true;
            $setting->activation_key = $request->key;
            $setting->save();

            return redirect('/')->with('success', 'System successfully activated.');
        }

        return back()->withErrors(['key' => 'Invalid activation key']);
    }
}
