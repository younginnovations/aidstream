<?php
namespace App\Core\V201\Repositories;

use App\Core\Repositories\SettingsRepositoryInterface;
use App\Models\Organization\OrganizationData;
use App\Models\Settings;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\SessionManager;
use Psr\Log\LoggerInterface;

class SettingsRepository implements SettingsRepositoryInterface
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;
    /**
     * @var LoggerInterface
     */
    protected $loggerInterface;
    /**
     * @var OrganizationData
     */
    protected $organizationData;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @param Settings         $settings
     * @param OrganizationData $organizationData
     * @param SessionManager   $sessionManager
     * @param DatabaseManager  $databaseManager
     * @param LoggerInterface  $loggerInterface
     */
    function __construct(Settings $settings, OrganizationData $organizationData, SessionManager $sessionManager, DatabaseManager $databaseManager, LoggerInterface $loggerInterface)
    {
        $this->databaseManager  = $databaseManager;
        $this->loggerInterface  = $loggerInterface;
        $this->organizationData = $organizationData;
        $this->sessionManager   = $sessionManager;
        $this->settings         = $settings;
    }

    /**
     * @param $organization_id
     * @return mixed
     */
    public function getSettings($organization_id)
    {
        return $this->settings->where('organization_id', $organization_id)->first();
    }

    /**
     * @param $input
     * @param $organization
     */
    public function storeSettings($input, $organization)
    {
        try {
            $this->databaseManager->beginTransaction();
            $organization->reporting_org = $input['reporting_organization_info'];
            $organization->save();

            $version = $input['version_form'][0]['version'];
            $this->sessionManager->put('version', 'V' . str_replace('.', '', $version));

            Settings::create(
                [
                    'publishing_type'      => $input['publishing_type'][0]['publishing'],
                    'registry_info'        => $input['registry_info'],
                    'default_field_values' => $input['default_field_values'],
                    'default_field_groups' => $input['default_field_groups'],
                    'version'              => $version,
                    'organization_id'      => $organization->id,
                ]
            );
            $this->organizationData->create(
                [
                    'organization_id' => $organization->id,
                ]
            );
            $this->databaseManager->commit();
            $this->loggerInterface->info('Organization Settings Inserted');

            return true;
        } catch (Exception $exception) {
            $this->databaseManager->rollback();

            $this->loggerInterface->error(
                sprintf('Settings could no be updated due to %s', $exception->getMessage()),
                [
                    'settings' => $input,
                    'trace'    => $exception->getTraceAsString()
                ]
            );

            return false;
        }

    }

    /**
     * @param $input
     * @param $organization
     * @param $settings
     */
    public function updateSettings($input, $organization, $settings)
    {
        try {
            $this->databaseManager->beginTransaction();
            $organization->reporting_org = $input['reporting_organization_info'];
            $organization->save();

            $version = $input['version_form'][0]['version'];
            $this->sessionManager->put('version', 'V' . str_replace('.', '', $version));

            $settings->publishing_type      = $input['publishing_type'][0]['publishing'];
            $settings->registry_info        = $input['registry_info'];
            $settings->default_field_values = $input['default_field_values'];
            $settings->default_field_groups = isset($input['default_field_groups']) ? $input['default_field_groups'] : [];
            $settings->version              = $version;
            $settings->organization_id      = $organization->id;
            $settings->save();
            $this->organizationData->firstOrCreate(['organization_id' => $organization->id,]);
            $this->databaseManager->commit();
            $this->loggerInterface->info('Organization Settings Updated');
        } catch (Exception $exception) {
            $this->databaseManager->rollback();

            $this->loggerInterface->error(
                sprintf('Settings could no be updated due to %s', $exception->getMessage()),
                [
                    'settings' => $input,
                    'trace'    => $exception->getTraceAsString()
                ]
            );
        }
    }
}
