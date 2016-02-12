<?php namespace App\Migration;

use App\Migration\MigrateHelper;
use Illuminate\Database\DatabaseManager;
use App\Migration\Elements\Settings;

class MigrateSettings
{
    protected $orgId;
    protected $mysqlConn;

    function __construct(MigrateHelper $migrateHelper, Settings $settings)
    {
        $this->migrateHelper = $migrateHelper;
        $this->settings      = $settings;
    }

    public function SettingsDataFetch($orgId, $accountId)
    {
        $this->initDBConnection('mysql');

        $registryInfo = $this->mysqlConn->table('registry_info')
                                        ->select('*')
                                        ->where('org_id', '=', $accountId)
                                        ->first();

        if ($registryInfo->publishing_type == 1) {
            $publishing_type = 'segmented';
        } else {
            $publishing_type = 'unsegmented';
        }
        if ($registryInfo->update_registry == 1) {
            $publish_files = 'yes';
        } else {
            $publish_files = 'no';
        }

        $registryInfoData = array(
            'publisher_id'  => $registryInfo->publisher_id,
            'api_key'       => $registryInfo->api_key,
            'publish_files' => $publish_files,
        );

        $default_field_groups = $this->mysqlConn->table('default_field_groups')
                                                ->select('*')
                                                ->where('account_id', '=', $accountId)
                                                ->first();

        $default_field_values = $this->mysqlConn->table('default_field_values')
                                                ->select('*')
                                                ->where('account_id', '=', $accountId)
                                                ->first();

        $temp_object_values          = unserialize($default_field_values->object);
        $arrayDefaultFieldValues     = (array) $temp_object_values;
        $formatDefaultFieldValues    = $this->settings->formatDefaultFieldValues($arrayDefaultFieldValues);
        $temp_object                 = unserialize($default_field_groups->object);
        $arrayDefaultFieldGroups     = (array) $temp_object;
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

    protected function initDBConnection($connection)
    {
        $this->mysqlConn = app()->make(DatabaseManager::class)->connection($connection);
    }
}
