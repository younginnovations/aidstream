<?php namespace App\Core\V202\Repositories\Organization;

use App\Models\Organization\OrganizationData;

/**
 * Class TotalExpenditure
 * @package App\Core\V202\Repositories\Organization
 */
class TotalExpenditure
{
    /**
     * @var OrganizationData
     */
    protected $org;

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
        $organization->total_expenditure = $input['total_expenditure'];

        return $organization->save();
    }

    /**
     * get organization data
     * @param $orgId
     * @return model
     */
    public function getOrganizationData($orgId)
    {
        return $this->org->where('organization_id', $orgId)->first();
    }

    /**
     * get total expenditure organization data
     * @param $orgId
     * @return model
     */
    public function getOrganizationTotalExpenditureData($orgId)
    {
        return $this->org->where('organization_id', $orgId)->first()->total_expenditure;
    }
}
