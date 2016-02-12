<?php namespace App\Migration\Migrator\Data;

use App\Migration\Elements\Settings;

/**
 * Class SettingsQuery
 * @package App\Migration\Migrator\Data
 */
class SettingsQuery extends Query
{
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * SettingsQuery constructor.
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     *
     * @param $accountIds
     * @return array
     */
    public function executeFor($accountIds)
    {
        $this->initDBConnection();

        $data = [];

        foreach ($accountIds as $accountId) {
            if ($organization = getOrganizationFor($accountId)) {
                $data[] = $this->getData($organization->id, $accountId);
            }
        }

        return $data;
    }

    /**
     * @param $organizationId
     * @param $accountId
     * @return array
     */
    protected function getData($organizationId, $accountId)
    {
        $registryInfo = $this->connection->table('registry_info')
                                         ->select('*')
                                         ->where('org_id', '=', $accountId)
                                         ->first();

        $default_field_groups = $this->connection->table('default_field_groups')
                                                 ->select('*')
                                                 ->where('account_id', '=', $accountId)
                                                 ->first();

        $default_field_values = $this->connection->table('default_field_values')
                                                 ->select('*')
                                                 ->where('account_id', '=', $accountId)
                                                 ->first();

        $publishing_type = ($registryInfo->publishing_type == 1) ? 'segmented' : 'unsegmented';
        $publish_files   = ($registryInfo->update_registry == 1) ? 'yes' : 'no';

        $registryInfoData = array(
            'publisher_id'  => $registryInfo ? ($registryInfo->publisher_id) : '',
            'api_key'       => $registryInfo ? ($registryInfo->api_key) : '',
            'publish_files' => $publish_files,
        );

        $temp_object_values      = unserialize($default_field_values->object);
        $arrayDefaultFieldValues = (array) $temp_object_values;
        $temp_object             = unserialize($default_field_groups->object);
        $arrayDefaultFieldGroups = (array) $temp_object;

        $formatDefaultFieldValues    = $this->settings->formatDefaultFieldValues($arrayDefaultFieldValues);
        $formattedDefaultFieldGroups = $this->settings->formatDefaultFieldGroups($arrayDefaultFieldGroups);

        $newSettingsDataFormat = array(
            'publishing_type'      => $publishing_type,
            'registry_info'        => $registryInfoData,
            'default_field_values' => $formatDefaultFieldValues,
            'default_field_groups' => $formattedDefaultFieldGroups,
            'organization_id'      => $accountId
        );

        return $newSettingsDataFormat;
    }
}
