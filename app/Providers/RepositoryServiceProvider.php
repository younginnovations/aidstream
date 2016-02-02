<?php namespace App\Providers;

use App\Migration\Migrator\ActivityMigrator;
use App\Migration\Migrator\Contract\MigratorContract;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\SuperAdmin\Repositories\SuperAdminInterfaces\SuperAdmin',
            'App\SuperAdmin\Repositories\SuperAdmin'
        );

        $this->app->bind(
            'App\SuperAdmin\Repositories\SuperAdminInterfaces\OrganizationGroup',
            'App\SuperAdmin\Repositories\OrganizationGroup'
        );
    }
}
