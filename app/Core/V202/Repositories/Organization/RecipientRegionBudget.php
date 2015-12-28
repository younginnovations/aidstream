<?php namespace App\Core\V202\Repositories\Organization;

use App\Models\Organization\OrganizationData;

/**
 * Class RecipientRegionBudget
 * @package App\Core\V202\Repositories\Organization
 */
class RecipientRegionBudget
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
        $organization->recipient_region_budget = $input['recipient_region_budget'];

        return $organization->save();
    }

    /**
     * return organization data
     * @param $organization_id
     * @return model
     */
    public function getOrganizationData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first();
    }

    /**
     * return recipient region budget data
     * @param $organization_id
     * @return model
     */
    public function getRecipientRegionBudgetData($organization_id)
    {
        return $this->org->where('organization_id', $organization_id)->first()->recipient_region_budget;
    }
}
