<?php
namespace App\Core\V201\Repositories\Organization;

use App\Models\Organization\Organization;

class OrgReportingOrgRepository
{

    /**
     * @param $input
     * @param $organization
     */
    public function update(array $input, Organization $organization)
    {
        $organization->reporting_org = $input['reporting_org'];
        $organization->save();
    }
}
