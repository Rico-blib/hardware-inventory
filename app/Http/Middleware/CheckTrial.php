<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Carbon\Carbon;

class CheckTrial
{
    public function handle(Request $request, Closure $next)
    {
        // Try to get the first setting
        $setting = Setting::first();

        // ✅ Auto-create if not found (prevents 403 lockout)
        if (!$setting) {
            $setting = Setting::create([
                'app_name' => 'Inventory system',
                'logo_path' => null,
                'login_background_color' => '#ffffff',
                'login_background_image' => null,
                'contact_number' => '0700000000',
                'email' => 'support@inventory.test',
                'installed_at' => now(),
                'activated' => false,
                'activation_key' => null,
            ]);
        }

        // ✅ If app is activated, allow full access
        if ($setting->activated) {
            return $next($request);
        }

        // ✅ Allow access during trial period (7 days from install)
        if ($setting->installed_at) {
            $expiry = Carbon::parse($setting->installed_at)->addDays(7);
            if (now()->lt($expiry)) {
                return $next($request);
            }
        }

        // ❌ Trial expired — redirect to activation
        return redirect()->route('activate.license');
    }
}
