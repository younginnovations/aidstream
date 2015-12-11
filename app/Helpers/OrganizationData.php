<?php namespace App\Helpers;

use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData as OrgData;
use Illuminate\Support\Facades\Session;

/**
 * Class OrganizationData
 * @package App\Helpers
 */
class OrganizationData
{
    /**
     * return all organization data
     */
    public function get()
    {
        $id                       = Session::get('org_id');
        $orgData                  = OrgData::where('organization_id', $id)->first()->toArray();
        $orgData['reporting_org'] = Organization::find($id)->reporting_org;

        return $orgData;
    }
}
