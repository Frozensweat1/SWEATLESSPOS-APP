<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;

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
    public function boot()
    {
        Paginator::useBootstrapFive();
        view()->composer('*', function ($view) {
            $settings = Setting::first() ?? new Setting([
                'site_name' => config('app.name', 'Sweatless POS'),
                'currency' => 'GHS',
                'contact_phone' => '',
                'contact_email' => '',
                'address' => '',
                'receipt_header' => 'In God We Trust!',
            ]);
            $view->with('settings', $settings);
        });
    }
}
