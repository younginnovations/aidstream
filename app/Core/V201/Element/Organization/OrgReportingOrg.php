<?php
namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;
use App\Models\Organization\Organization;

class OrgReportingOrg extends BaseElement
{
    protected $type;
    protected $narratives = [];

    /**
     * @param $narrative
     * @return $this
     */
    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    /**
     * @return string
     */
    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\ReportingOrganizationInfoForm";
    }


    /**
     * @param Organization $organization
     * @return array
     */
    public function getXmlData(Organization $organization)
    {
        $organizationData = [];
        $orgReportingOrg  = (array) $organization->buildOrgReportingOrg();
        foreach ($orgReportingOrg as $OrgReportingOrg) {
            $organizationData[] = [
                '@attributes' => [
                    'type' => $OrgReportingOrg['reporting_organization_type'],
                    'ref' => $OrgReportingOrg['reporting_organization_identifier']
                ],
                'narrative'   => $this->buildNarrative($OrgReportingOrg['narrative']),
            ];
        }

        return $organizationData;
    }

    /**
     * @return organization reporting  organization repository
     */
    public function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\OrgReportingOrgRepository');
    }
}