<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DevelopmentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services only for development.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider');
        if (getenv('APP_ENV') == "local"){

        }
    }
}
