<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NameRepository
{
    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organizationData)
    {
        try{
            DB::beginTransaction();
            $organizationData->name = json_encode($input['name']);
            $organizationData->save();
            DB::commit();
            Log::info('Organization Name Updated',
                ['for ' => $organizationData['name']]);
        } catch (Exception $exception) {
            DB::rollback();

            Log::error(
                sprintf('Organization Name could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationName' => $input,
                    'trace' => $exception->getTraceAsString()
                ]
            );
        }
    }

    public function getOrganizationData($organization_id)
    {
        return OrganizationData::where('organization_id', $organization_id)->first();
    }

    public function getOrganizationNameData($organization_id)
    {
        return json_decode(OrganizationData::where('organization_id', $organization_id)->first()->name, true);
    }

}