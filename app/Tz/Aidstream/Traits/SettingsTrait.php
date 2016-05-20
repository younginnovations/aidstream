<?php namespace App\Tz\Aidstream\Traits;

use App\Models\Organization\Organization;
use App\Tz\Aidstream\Models\Settings;

/**
 * Class SettingsTrait
 * @package App\Tz\Aidstream\Traits
 */
trait SettingsTrait
{
    /**
     * Format form data into json to save in DB
     * @param array $settings
     * @param       $version
     * @return array
     */
    public function formatsFormDataIntoJson(array $settings, $version)
    {
        $registryInfo = [
            [
                'publisher_id'  => $settings['publisher_id'],
                'api_id'        => $settings['api_id'],
                'publish_files' => $settings['publish_files']
            ]
        ];

        $defaultFieldValues = [
            [
                'default_language' => $settings['default_language'],
                'default_currency' => $settings['default_currency']
            ]
        ];

        $array = [
            'registry_info'        => $registryInfo,
            'default_field_values' => $defaultFieldValues,
            'version'              => $version
        ];

        return $array;
    }

    /**
     * Change DB Json data into array
     * @param Settings     $settings
     * @param Organization $organization
     * @return array
     */
    public function formatsDBDataIntoColumn(Settings $settings, Organization $organization)
    {
        $organization = $organization->toArray();

        return [
            'id'                       => $settings->id,
            'reporting_org_identifier' => getVal($organization, ['reporting_org', 0, 'reporting_organization_identifier']),
            'reporting_org_type'       => getVal($organization, ['reporting_org', 0, 'reporting_organization_type']),
            'narrative'                => getVal($organization, ['reporting_org', 0, 'narrative', 0, 'narrative']),
            'language'                 => getVal($organization, ['reporting_org', 0, 'narrative', 0, 'language']),
            'publisher_id'             => getVal($settings->registry_info, [0, 'publisher_id']),
            'api_id'                   => getVal($settings->registry_info, [0, 'api_id']),
            'publish_files'            => getVal($settings->registry_info, [0, 'publish_files']),
            'default_currency'         => getVal($settings->default_field_values, [0, 'default_currency']),
            'default_language'         => getVal($settings->default_field_values, [0, 'default_language'])
        ];
    }

    /**
     * Format reporting organization data into json
     * @param $settings
     * @return array
     */
    public function formatsReportingOrgData($settings)
    {
        return [
            'reporting_org' => [
                [
                    'reporting_organization_identifier' => $settings['reporting_org_identifier'],
                    'reporting_organization_type'       => $settings['reporting_org_type'],
                    'narrative'                         => [
                        [
                            'narrative' => $settings['narrative'],
                            'language'  => $settings['language']
                        ]
                    ]
                ]
            ]
        ];
    }
}
