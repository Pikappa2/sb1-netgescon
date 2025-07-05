<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
    public function boot(): void
    {
        // View Composer per la sidebar
        View::composer(['components.menu.sidebar', 'superadmin.dashboard', 'admin.dashboard'], \App\Http\View\Composers\SidebarComposer::class);
    }
}
