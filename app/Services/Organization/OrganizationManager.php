<?php
namespace App\Services\Organization;

use App\Core\Version;
use App;
use App\Helpers\ArrayToXml;

class OrganizationManager
{

    protected $repo;

    function __construct(Version $version, ArrayToXml $arrayToXml)
    {
        $this->version = $version;
        $this->repo = $version->getOrganizationElement()->getRepository();
        $this->orgElement = $version->getOrganizationElement();
        $this->arrayToXml = $arrayToXml;
    }


    public function createOrganization(array $input)
    {
        $this->repo->createOrganization($input);
    }

    public function getOrganizations()
    {
        return $this->repo->getOrganizations();
    }

    public function getOrganization($id)
    {
        return $this->repo->getOrganization($id);

    }

    public function updateOrganization($input, $organization)
    {
        $this->repo->updateOrganization($input, $organization);
    }

    public function generateXmlFile($organization, $organizationData, $settings)
    {
        $xmlData = array();
        $xmlData['@attributes'] = array(
            'version' => $settings->version,
            'generated-datetime' => gmdate('c')
        );
        $xmlData['iati-organisation'] = $this->getXmlData($organization, $organizationData);
        $xmlData['iati-organisation']['@attributes'] = array(
            'last-updated-datetime' => gmdate('c', time($settings->updated_at)),
            'xml:lang' => $settings->default_field_values[0]['default_language'],
            'default-currency' => $settings->default_field_values[0]['default_currency']
        );
        $xml = $this->arrayToXml->createXML('iati-organisations', $xmlData);
        $filename = $organization->buildOrgReportingOrg()[0]['reporting_organization_identifier'] . '.xml';
        $xml->save(public_path('uploads/files/organization/' . $filename));
    }

    public function getXmlData ($organization, $organizationData) {
        $xmlOrganization = [];
        $xmlOrganization['organisation-identifier'] = $organization->buildOrgReportingOrg()[0]['reporting_organization_identifier'];
        $xmlOrganization['name'] = $this->orgElement->getName()->getXmlData($organizationData);
        $xmlOrganization['reporting-org'] = $this->orgElement->getOrgReportingOrg()->getXmlData($organization);
        $xmlOrganization['total-budget'] = $this->orgElement->getTotalBudget()->getXmlData($organizationData);
        $xmlOrganization['recipient-org-budget'] = $this->orgElement->getRecipientOrgBudget()->getXmlData($organizationData);
        $xmlOrganization['recipient-country-budget'] = $this->orgElement->getRecipientCountryBudget()->getXmlData($organizationData);
        $xmlOrganization['document-link'] = $this->orgElement->getDocumentLink()->getXmlData($organizationData);
        return $xmlOrganization;
    }


}