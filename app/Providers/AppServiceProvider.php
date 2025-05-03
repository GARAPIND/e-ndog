<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ProfileToko;
use Illuminate\Support\Facades\View;

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
        // Share ProfileToko data with all views
        View::composer('*', function ($view) {
            $profileToko = ProfileToko::first();
            $view->with('profileToko', $profileToko);
        });
    }
}
