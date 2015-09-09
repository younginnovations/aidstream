<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipientCountryBudgetRepository
{
    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        try{
            DB::beginTransaction();
            $organization->recipient_country_budget = $input['recipientCountryBudget'];
            $organization->save();
            DB::commit();
            Log::info('Recipient Country Budget Updated',
                ['for ' => $organization['recipient_country_budget']]);
        } catch (Exception $exception) {
            DB::rollback();

            Log::error(
                sprintf('Recipient Country Budget could not be updated due to %s', $exception->getMessage()),
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

    public function getRecipientCountryBudgetData($organization_id)
    {
        return OrganizationData::where('organization_id', $organization_id)->first()->recipient_country_budget;
    }

}