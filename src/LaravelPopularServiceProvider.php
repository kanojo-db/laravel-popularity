<?php

namespace KanojoDb\LaravelPopular;

use Illuminate\Support\ServiceProvider;

class LaravelPopularServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        //
    }
}
