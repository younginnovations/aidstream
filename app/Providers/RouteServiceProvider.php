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
                require app_path('Http/routes/Lite/lite.php');
                require app_path('Http/routes/Np/np.php');
                require app_path('Http/routes/Np/activity.php');
                require app_path('Http/routes/Np/publishedFiles.php');
                require app_path('Http/routes/Np/users.php');
                require app_path('Http/routes/Np/settings.php');
                require app_path('Http/routes/Np/workflow.php');
                require app_path('Http/routes/Np/profile.php');
                require app_path('Http/routes/Np/download.php');
                require app_path('Http/routes/Np/municipalityAdmin.php');
                require app_path('Http/routes/login.php');
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
                require app_path('Http/routes/import/import.php');
                require app_path('Http/routes/import/result/importresult.php');
                require app_path('Http/routes/import/transaction/importTransaction.php');
                require app_path('Http/routes/xmlImport.php');
                require app_path('Http/routes/bulkPublish.php');
                require app_path('Http/routes/Lite/settings.php');
                require app_path('Http/routes/Lite/users.php');
                require app_path('Http/routes/Lite/workflow.php');
                require app_path('Http/routes/Lite/profile.php');
                require app_path('Http/routes/Lite/publishedFiles.php');
                require app_path('Http/routes/Lite/download.php');
            }
        );
    }
}
