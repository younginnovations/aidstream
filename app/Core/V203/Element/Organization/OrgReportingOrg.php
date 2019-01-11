<?php
namespace App\Core\V203\Element\Organization;

use App\Core\V201\Element\Organization\OrgReportingOrg as V201;
use App\Core\Elements\BaseElement;
use App;
use App\Models\Organization\Organization;

class OrgReportingOrg extends V201
{
    /**
     * @param Organization $organization
     * @return array
     */
    public function getXmlData(Organization $organization)
    {
        $organizationData = [];
        $orgReportingOrg  = (array) $organization->reporting_org;
        foreach ($orgReportingOrg as $OrgReportingOrg) {
            $organizationData[] = [
                '@attributes' => [
                    'type'                  => $OrgReportingOrg['reporting_organization_type'],
                    'ref'                   => $OrgReportingOrg['reporting_organization_identifier'],
                    'secondary-reporter'    => $organization->secondary_reporter
                ],
                'narrative'   => $this->buildNarrative($OrgReportingOrg['narrative']),
            ];
        }

        return $organizationData;
    }
}
