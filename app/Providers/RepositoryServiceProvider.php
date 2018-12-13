<?php namespace App\Providers;

use App\Lite\Contracts\ActivityRepositoryInterface;
use App\Lite\Contracts\DocumentLinkRepositoryInterface;
use App\Lite\Contracts\OrganisationRepositoryInterface;
use App\Lite\Contracts\PublishedFilesRepositoryInterface;
use App\Lite\Contracts\SettingsRepositoryInterface;
use App\Lite\Contracts\TransactionRepositoryInterface;
use App\Lite\Contracts\UserRepositoryInterface;
use App\Lite\Repositories\Activity\ActivityRepository;
use App\Lite\Repositories\DocumentLinks\DocumentLinksRepository;
use App\Lite\Repositories\Organisation\OrganisationRepository;
use App\Lite\Repositories\PublishedFiles\PublishedFilesRepository;
use App\Lite\Repositories\Settings\SettingsRepository;
use App\Lite\Repositories\Activity\Transaction\TransactionRepository;
use App\Lite\Repositories\Users\UserRepository;
use App\Np\Contracts\NpActivityRepositoryInterface;
use App\Np\Contracts\ActivityLocationRepositoryInterface;
use App\Np\Contracts\NpDocumentLinkRepositoryInterface;
use App\Np\Contracts\NpOrganizationRepositoryInterface;
use App\Np\Contracts\NpPublishedFilesRepositoryInterface;
use App\Np\Contracts\NpSettingsRepositoryInterface;
use App\Np\Contracts\NpTransactionRepositoryInterface;
use App\Np\Contracts\NpUserRepositoryInterface;
use App\Np\Repositories\Activity\ActivityLocationRepository;
use App\Np\Repositories\Activity\NpActivityRepository;
use App\Np\Repositories\DocumentLinks\NpDocumentLinksRepository;
use App\Np\Repositories\Organization\NpOrganizationRepository;
use App\Np\Repositories\PublishedFiles\NpPublishedFilesRepository;
use App\Np\Repositories\Settings\NpSettingsRepository;
use App\Np\Repositories\Activity\Transaction\NpTransactionRepository;
use App\Np\Repositories\Users\NpUserRepository;
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

        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(OrganisationRepositoryInterface::class, OrganisationRepository::class);
        $this->app->bind(SettingsRepositoryInterface::class, SettingsRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(DocumentLinkRepositoryInterface::class, DocumentLinksRepository::class);
        $this->app->bind(PublishedFilesRepositoryInterface::class, PublishedFilesRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(NpActivityRepositoryInterface::class, NpActivityRepository::class);
        $this->app->bind(ActivityLocationRepositoryInterface::class, ActivityLocationRepository::class);
        $this->app->bind(NpUserRepositoryInterface::class, NpUserRepository::class);
        $this->app->bind(NpOrganizationRepositoryInterface::class, NpOrganizationRepository::class);
        $this->app->bind(NpSettingsRepositoryInterface::class, NpSettingsRepository::class);
        $this->app->bind(NpUserRepositoryInterface::class, NpUserRepository::class);
        $this->app->bind(NpDocumentLinkRepositoryInterface::class, NpDocumentLinksRepository::class);
        $this->app->bind(NpPublishedFilesRepositoryInterface::class, NpPublishedFilesRepository::class);
        $this->app->bind(NpTransactionRepositoryInterface::class, NpTransactionRepository::class);
    }
}
