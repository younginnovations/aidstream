<?php
namespace App\Core\V201\Repositories;

use App\Core\Repositories\SettingsRepositoryInterface;
use App\Models\Organization\OrganizationData;
use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SettingsRepository implements SettingsRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getSettings($organization_id)
    {
        return Settings::where('organization_id', $organization_id)->first();
    }

    /**
     * @param $input
     * @param $organization
     */
    public function storeSettings($input, $organization)
    {
        try {
            DB::beginTransaction();
            $organization->reporting_org = json_encode($input['reporting_organization_info']);
            $organization->save();

            $version = $input['version_form'][0]['version'];
            Session::put('version', $version);

            Settings::create([
                'publishing_type' => $input['publishing_type'][0]['publishing'],
                'registry_info' => json_encode($input['registry_info']),
                'default_field_values' => json_encode($input['default_field_values']),
                'default_field_groups' => json_encode($input['default_field_groups']),
                'version' => $version,
                'organization_id' => $organization->id,
            ]);
            OrganizationData::create([
                'organization_id' => $organization->id,
            ]);
            DB::commit();
            Log::info('Organization Settings Inserted');
        } catch (Exception $exception) {
            DB::rollback();

            Log::error(
                sprintf('Settings could no be updated due to %s', $exception->getMessage()),
                [
                    'settings' => $input,
                    'trace' => $exception->getTraceAsString()
                ]
            );
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
            DB::beginTransaction();
            $organization->reporting_org = json_encode($input['reporting_organization_info']);
            $organization->save();

            $version = $input['version_form'][0]['version'];
            Session::put('version', $version);

            $settings->publishing_type = $input['publishing_type'][0]['publishing'];
            $settings->registry_info = json_encode($input['registry_info']);
            $settings->default_field_values = json_encode($input['default_field_values']);
            $settings->default_field_groups = json_encode($input['default_field_groups']);
            $settings->version = $version;
            $settings->organization_id = $organization->id;
            $settings->save();
            DB::commit();
            Log::info('Organization Settings Updated');
        } catch (Exception $exception) {
            DB::rollback();

            Log::error(
                sprintf('Settings could no be updated due to %s', $exception->getMessage()),
                [
                    'settings' => $input,
                    'trace' => $exception->getTraceAsString()
                ]
            );
        }
    }

}