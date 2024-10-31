<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia; // Import Inertia

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
        Vite::prefetch(concurrency: 3);
        
        // Share flash messages with Inertia
        Inertia::share([
            'flash' => function () {
                return [
                    'success' => session()->get('success'),
                    'error' => session()->get('error'),
                ];
            },
        ]);
    }
}