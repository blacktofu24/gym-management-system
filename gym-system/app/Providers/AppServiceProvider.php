<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force secure HTTPS links if accessed via Ngrok
        if (str_contains(request()->getHost(), 'loca.lt')) {
            URL::forceScheme('https');
        }
    }
}