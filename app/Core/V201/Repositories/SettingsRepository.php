<?php namespace App\Core\V201\Repositories;

use App\Core\Repositories\SettingsRepositoryInterface;
use App\Models\Organization\OrganizationData;
use App\Models\Settings;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\SessionManager;

class SettingsRepository implements SettingsRepositoryInterface
{
    /**
     * @var DatabaseManager
     */
    protected $databaseManager;
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
     */
    function __construct(Settings $settings, OrganizationData $organizationData, SessionManager $sessionManager, DatabaseManager $databaseManager)
    {
        $this->databaseManager  = $databaseManager;
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
     * @return bool
     */
    public function storeSettings($input, $organization)
    {
        $organization->reporting_org = $input['reporting_organization_info'];
        $organization->save();

        Settings::create(
            [
                'publishing_type'      => $input['publishing_type'][0]['publishing'],
                'registry_info'        => $input['registry_info'],
                'default_field_values' => $input['default_field_values'],
                'default_field_groups' => $input['default_field_groups'],
                'organization_id'      => $organization->id,
            ]
        );

        $this->organizationData->create(['organization_id' => $organization->id]);

    }

    /**
     * @param $input
     * @param $organization
     * @param $settings
     * @return bool
     */
    public function updateSettings($input, $organization, $settings)
    {
        $organization->reporting_org = $input['reporting_organization_info'];
        $organization->save();

        $settings->publishing_type      = $input['publishing_type'][0]['publishing'];
        $settings->registry_info        = $input['registry_info'];
        $settings->default_field_values = $input['default_field_values'];
        $settings->default_field_groups = isset($input['default_field_groups']) ? $input['default_field_groups'] : [];
        $settings->organization_id      = $organization->id;
        $settings->save();

        $this->organizationData->firstOrCreate(['organization_id' => $organization->id,]);
    }
}
