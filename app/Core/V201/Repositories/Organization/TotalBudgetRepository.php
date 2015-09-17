<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;

class TotalBudgetRepository
{
    /**
     * @var OrganizationData
     */
    private $org;

    /**
     * @param OrganizationData $org
     */
    function __construct(OrganizationData $org)
    {
        $this->org = $org;
    }

    /**
     * @param $input
     * @param $organization
     * @return bool
     */
    public function update(array $input, OrganizationData $organization)
    {
        $organization->total_budget = $input['totalBudget'];

        return $organization->save();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getOrganizationData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getOrganizationTotalBudgetData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first()->total_budget;
    }

}