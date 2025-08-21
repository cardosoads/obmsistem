<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar o Laravel Pail apenas em ambiente de desenvolvimento
        if ($this->app->environment('local', 'testing') && class_exists('Laravel\Pail\PailServiceProvider')) {
            $this->app->register('Laravel\Pail\PailServiceProvider');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
