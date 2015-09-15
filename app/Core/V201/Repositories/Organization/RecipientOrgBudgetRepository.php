<?php
namespace App\Core\V201\Repositories\Organization;


use App\Models\Organization\OrganizationData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecipientOrgBudgetRepository
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
     * @param DB               $database
     * @param Log              $log
     */
    function __construct(OrganizationData $org, DB $database, Log $log)
    {
        $this->org      = $org;
        $this->database = $database;
        $this->log      = $log;
    }

    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        $organization->recipient_organization_budget = $input['recipientOrganizationBudget'];
        return $organization->save();
    }

    public function getOrganizationData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first();
    }

    public function getRecipientOrgBudgetData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first()->recipient_organization_budget;
    }
}