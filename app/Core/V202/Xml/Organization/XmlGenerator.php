<?php namespace App\Core\V202\Xml\Organization;

use App\Core\V201\Element\Organization\XmlGenerator as V201XmlGenerator;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;

/**
 * Class XmlGenerator
 * @package app\Core\V202\Element\Organization
 */
class XmlGenerator extends V201XmlGenerator
{
    /**
     * returns xml data from all elements
     * @param Organization     $organization
     * @param OrganizationData $organizationData
     * @return array
     */
    public function getXmlData(Organization $organization, OrganizationData $organizationData)
    {
        $xmlOrganization                             = [];
        $xmlOrganization['organisation-identifier']  = $organization->reporting_org[0]['reporting_organization_identifier'];
        $xmlOrganization['name']                     = $this->nameElem->getXmlData($organizationData);
        $xmlOrganization['reporting-org']            = $this->reportingOrgElem->getXmlData($organization);
        $xmlOrganization['total-budget']             = $this->totalBudgetElem->getXmlData($organizationData);
        $xmlOrganization['recipient-org-budget']     = $this->recipientOrgBudgetElem->getXmlData($organizationData);
        $xmlOrganization['recipient-region-budget']  = $this->orgElem->getRecipientRegionBudgetXml($organizationData);
        $xmlOrganization['recipient-country-budget'] = $this->recipientCountrybudgetElem->getXmlData($organizationData);
        $xmlOrganization['total-expenditure']        = $this->orgElem->getTotalExpenditureXml($organizationData);
        $xmlOrganization['document-link']            = $this->documentLinkElem->getXmlData($organizationData);

        removeEmptyValues($xmlOrganization);

        return $xmlOrganization;
    }
}
