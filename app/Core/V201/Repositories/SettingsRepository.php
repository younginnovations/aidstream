<?php namespace App\Core\V201\Repositories;

use App\Core\Repositories\SettingsRepositoryInterface;
use App\Models\Organization\OrganizationData;
use App\Models\Settings;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

    /**
     * save activity elements checklist
     * @param $default_field_groups
     * @param $settings
     */
    public function saveActivityElementsChecklist($default_field_groups, $settings)
    {
        if ($settings) {
            $settings->default_field_groups = $default_field_groups;
            $settings->save();
        } else {
            $this->settings->create(['default_field_groups' => $default_field_groups, 'organization_id' => session('org_id')]);
        }
    }

    /**
     * Save default field values
     * @param $defaultValues
     * @param $settings
     */
    public function saveDefaultValues($defaultValues, $settings)
    {
        if ($settings) {
            $settings->default_field_values = [$defaultValues];
            $settings->save();
        } else {
            $this->settings->create(['default_field_values' => $defaultValues, 'organization_id' => session('org_id')]);
        }
    }

    /**
     * save publishing information
     * @param $publishing_info
     * @param $settings
     */
    public function savePublishingInfo($publishing_info, $settings)
    {
        $registry_info = [
            0 => [
                'publisher_id'        => $publishing_info['publisher_id'],
                'api_id'              => $publishing_info['api_id'],
                'publish_files'       => $publishing_info['publish_files'],
                'publisher_id_status' => $publishing_info['publisher_id_status'],
                'api_id_status'       => $publishing_info['api_id_status']
            ]
        ];
        if ($settings) {
            $settings->publishing_type = $publishing_info['publishing'];
            $settings->registry_info   = $registry_info;
            $settings->save();
        } else {
            $this->settings->create(['registry_info' => $registry_info, 'publishing' => $publishing_info['publishing'], 'organization_id' => session('org_id')]);
        }

    }
}
