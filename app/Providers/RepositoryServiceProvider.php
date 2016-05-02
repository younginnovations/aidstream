<?php namespace App\Providers;

use App\Services\Settings\Segmentation\SegmentationInterface;
use App\Services\Settings\Segmentation\SegmentationService;
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

        $this->app->bind(
            SegmentationInterface::class,
            SegmentationService::class
        );
    }
}
