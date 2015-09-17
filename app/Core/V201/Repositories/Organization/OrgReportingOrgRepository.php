<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\Organization;

class OrgReportingOrgRepository
{
    /**
     * @param $organization
     * @param $input
     */
    public function create(Organization $organization, array $input)
    {
        $organization->reporting_org = json_encode($input['reportingOrg']);
        $organization->save();
    }

    /**
     * @param $input
     * @param $organization
     */
    public function update(array $input, Organization $organization)
    {
        $organization->reporting_org = json_encode($input['reportingOrg']);
        $organization->save();
    }

}