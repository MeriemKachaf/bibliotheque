<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Force HTTPS en production (Railway utilise un proxy HTTPS)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
