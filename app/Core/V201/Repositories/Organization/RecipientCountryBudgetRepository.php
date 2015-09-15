<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipientCountryBudgetRepository
{
    /**
     * @var OrganizationData
     */
    private $org;
    /**
     * @var DB
     */
    private $database;
    /**
     * @var Log
     */
    private $log;

    /**
     * @param OrganizationData $org
     * @param DB $database
     * @param Log $log
     */
    function __construct(OrganizationData $org, DB $database, Log $log)
    {
        $this->org = $org;
        $this->database = $database;
        $this->log = $log;
    }

    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        try{
            $this->database->beginTransaction();
            $organization->recipient_country_budget = $input['recipientCountryBudget'];
            $organization->save();
            $this->database->commit();
            $this->log->info('Recipient Country Budget Updated',
                ['for ' => $organization['recipient_country_budget']]);
        } catch (Exception $exception) {
            $this->database->rollback();

            $this->log->error(
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
        return $this->org->where('organization_id', $organization_id)->first();
    }

    public function getRecipientCountryBudgetData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first()->recipient_country_budget;
    }

}