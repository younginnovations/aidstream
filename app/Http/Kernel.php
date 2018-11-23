<?php namespace App\Http;

use App\Http\Middleware\AuthenticateAdmin;
use App\Http\Middleware\SystemVersion;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        'Illuminate\Foundation\Bootstrap\DetectEnvironment',
        'Illuminate\Foundation\Bootstrap\LoadConfiguration',
        'App\Bootstrap\ConfigureLogging',
        'Illuminate\Foundation\Bootstrap\HandleExceptions',
        'Illuminate\Foundation\Bootstrap\RegisterFacades',
        'Illuminate\Foundation\Bootstrap\RegisterProviders',
        'Illuminate\Foundation\Bootstrap\BootProviders',
    ];

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'App\Http\Middleware\VerifyCsrfToken',
        'App\Http\Middleware\Language',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'                   => 'App\Http\Middleware\Authenticate',
        'auth.superAdmin'        => 'App\Http\Middleware\AuthenticateSuperAdmin',
        'auth.municipalityAdmin' => 'App\Http\Middleware\AuthenticateMunicipalityAdmin',
        'auth.basic'             => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
        'guest'                  => 'App\Http\Middleware\RedirectIfAuthenticated',
        'auth.organizationAdmin' => 'App\Http\Middleware\AuthenticateOrganizationAdmin',
        'auth.admin'             => AuthenticateAdmin::class,
        'auth.systemVersion'     => SystemVersion::class
    ];

}
