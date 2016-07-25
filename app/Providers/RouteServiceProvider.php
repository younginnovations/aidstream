<?php namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        //
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(
            ['namespace' => $this->namespace],
            function ($router) {
                require app_path('Http/routes.php');
                require app_path('Http/routes/organization.php');
                require app_path('Http/routes/activity.php');
                require app_path('Http/routes/wizard/activity.php');
                require app_path('Http/routes/superAdmin.php');
                require app_path('Http/routes/organizationGroup.php');
                require app_path('Http/routes/user.php');
                require app_path('Http/routes/download.php');
                require app_path('Http/routes/Superadmin/publishedFilesCorrection.php');
                require app_path('Http/routes/settings.php');
                require app_path('Http/routes/userOnBoarding.php');
            }
        );
    }
}
