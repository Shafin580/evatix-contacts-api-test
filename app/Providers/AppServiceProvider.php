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
        //Registering our GenericResponses File as a Service Provider
        $this->app->singleton('GenericResponses', function ($app) {
            return new \App\Services\GenericResponses\GenericResponses;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
