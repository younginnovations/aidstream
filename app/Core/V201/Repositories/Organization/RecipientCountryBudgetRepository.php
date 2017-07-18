<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\OrganizationData;

class RecipientCountryBudgetRepository
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
        $organization->recipient_country_budget = $input['recipient_country_budget'];

        return $organization->save();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getOrganizationData($organization_id)
    {
        return $this->org->where('id', $organization_id)->first();
    }

    /**
     * write brief description
     * @param $organization_id
     * @return model
     */
    public function getRecipientCountryBudgetData($organization_id)
    {
        return $this->org->where('id', $organization_id)->first()->recipient_country_budget;
    }
}
