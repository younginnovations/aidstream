<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
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
            $this->app->register('Barryvdh\Debugbar\ServiceProvider');
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
