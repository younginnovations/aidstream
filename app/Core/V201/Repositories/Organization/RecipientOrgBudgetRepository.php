<?php
namespace App\Core\V201\Repositories\Organization;


use App\Models\Organization\OrganizationData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipientOrgBudgetRepository
{

    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        try{
            DB::beginTransaction();
            $organization->recipient_organization_budget = json_encode($input['recipientOrganizationBudget']);
            $organization->save();
            DB::commit();
            Log::info('Recipient organization budget updated',
                ['for ' => $organization['recipient_organization_budget']]);
        } catch (Exception $exception) {
            DB::rollback();

            Log::error(
                sprintf('Recipient organization budget could not be updated due to %s', $exception->getMessage()),
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

}