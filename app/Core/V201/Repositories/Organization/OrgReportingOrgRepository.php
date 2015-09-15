<?php
namespace App\Core\V201\Repositories\Organization;

class OrgReportingOrgRepository
{

    /**
     * @param $input
     * @param $organization
     */
    public function update($input, $organization)
    {
        $organization->reporting_org = json_encode($input['reportingOrg']);
        $organization->save();
    }

}