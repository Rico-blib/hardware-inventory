<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings globally with all views
        if (Schema::hasTable('settings')) {
            $setting = Setting::first();
            View::share('globalSetting', $setting);
        }

        if (Schema::hasTable('settings')) {
            $setting = Setting::first();
            if ($setting && is_null($setting->installed_at)) {
                $setting->installed_at = now();
                $setting->save();
            }
        }
    }
}
