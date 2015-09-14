<?php
namespace App\Core\V201\Element\Organization;

use App\Core\Elements\BaseElement;
use App;

class OrgReportingOrg extends BaseElement
{
    protected $type;
    protected $narratives = [];

    public function setNarrative($narrative)
    {
        $this->narratives[] = $narrative;
        return $this;
    }

    public function getForm()
    {
        return "App\Core\V201\Forms\Organization\ReportingOrganizationInfoForm";
    }

    /**
     * @param $organization
     * @return mixed
     */
    public function getXmlData($organization)
    {
        $organizationData =[];
        $orgReportingOrg = $organization->buildOrgReportingOrg();
        foreach ($orgReportingOrg as $OrgReportingOrg) {
            $organizationData[] = array(
                '@attributes' => array('type' => $OrgReportingOrg['type']),
                'narrative' => $this->buildNarrative($OrgReportingOrg['narrative'])
            );
        }
        return $organizationData;
    }

    /**
     * @return organization reporting  organization repository
     */
    public  function getRepository()
    {
        return App::make('App\Core\V201\Repositories\Organization\OrgReportingOrgRepository');
    }
}