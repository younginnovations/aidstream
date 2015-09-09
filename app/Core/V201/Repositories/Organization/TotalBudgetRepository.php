<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TotalBudgetRepository
{
    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        try{
            DB::beginTransaction();
            $organization->total_budget = json_encode($input['totalBudget']);
            $organization->save();
            DB::commit();
            Log::info('Organization Total Budget Updated',
                ['for ' => $organization['total_budget']]);
        } catch (Exception $exception) {
            DB::rollback();

            Log::error(
                sprintf('Organization Total Budget could not be updated due to %s', $exception->getMessage()),
                [
                    'OrganizationTotalBudget' => $input,
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
        return json_decode(OrganizationData::where('organization_id', $organization_id)->first()->total_budget);
    }

}