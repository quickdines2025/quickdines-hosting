<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\View::composer('layouts.admin', function ($view) {
            $pendingCount = \App\Models\Restaurant::where('status', 0)->count() + \App\Models\Driver::where('status', 0)->count();
            $view->with('pendingApprovalsCount', $pendingCount);
        });
    }
}
