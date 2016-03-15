<?php namespace App\Core\V201\Element\Organization;

use App\Helpers\ArrayToXml;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\Settings;
use App\Models\OrganizationPublished;
use App\Services\Organization\OrganizationManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class XmlGenerator
 * @package app\Core\V201\Element\Organization
 */
class XmlGenerator
{
    protected $arrayToXml;
    protected $orgElem;
    protected $nameElem;
    protected $reportingOrgElem;
    protected $totalBudgetElem;
    protected $recipientOrgBudgetElem;
    protected $recipientCountrybudgetElem;
    protected $documentLinkElem;
    protected $organizationManager;
    protected $organizationPublished;

    /**
     * @param ArrayToXml            $arrayToXml
     * @param OrganizationPublished $organizationPublished
     * @param OrganizationManager   $organizationManager
     */
    public function __construct(ArrayToXml $arrayToXml, OrganizationPublished $organizationPublished, OrganizationManager $organizationManager)
    {
        $this->arrayToXml            = $arrayToXml;
        $this->organizationPublished = $organizationPublished;
        $this->organizationManager   = $organizationManager;
    }

    /**
     * set elements to get individual xml data
     * @param $orgElem
     */
    public function setElements($orgElem)
    {
        $this->orgElem                    = $orgElem;
        $this->nameElem                   = $orgElem->getName();
        $this->reportingOrgElem           = $orgElem->getOrgReportingOrg();
        $this->totalBudgetElem            = $orgElem->getTotalBudget();
        $this->recipientOrgBudgetElem     = $orgElem->getRecipientOrgBudget();
        $this->recipientCountrybudgetElem = $orgElem->getRecipientCountryBudget();
        $this->documentLinkElem           = $orgElem->getDocumentLink();
    }

    /**
     * @param Organization     $organization
     * @param OrganizationData $organizationData
     * @param Settings         $settings
     * @param                  $orgElem
     * @return mixed
     */
    public function generateXml(Organization $organization, OrganizationData $organizationData, Settings $settings, $orgElem)
    {
        $xml      = $this->getXml($organization, $organizationData, $settings, $orgElem);
        $filename = $settings['registry_info'][0]['publisher_id'] . '-org.xml';

        $result = Storage::put(sprintf('%s/%s', config('filesystems.xml'), $filename), $xml->saveXML());

        if ($result) {
            $published = $this->organizationPublished->firstOrNew(['filename' => $filename, 'organization_id' => $organization->id]);
            $published->touch();
            $published->filename        = $filename;
            $published->organization_id = $organization->id;
            $published->save();
        }

        return ($settings['registry_info'][0]['publish_files'] == 'yes') ? $this->organizationManager->publishToRegistry($organization, $settings) : true;
    }

    /**
     * returns full xml data with xml data from all elements
     * @param Organization     $organization
     * @param OrganizationData $organizationData
     * @param Settings         $settings
     * @return \DomDocument
     */
    public function getXml(Organization $organization, OrganizationData $organizationData, Settings $settings, $orgElem)
    {
        $this->setElements($orgElem);
        $xmlData                                     = [];
        $xmlData['@attributes']                      = [
            'version'            => $settings->version,
            'generated-datetime' => gmdate('c')
        ];
        $xmlData['iati-organisation']                = $this->getXmlData($organization, $organizationData);
        $xmlData['iati-organisation']['@attributes'] = [
            'last-updated-datetime' => gmdate('c', time($settings->updated_at)),
            'xml:lang'              => $settings->default_field_values[0]['default_language'],
            'default-currency'      => $settings->default_field_values[0]['default_currency']
        ];

        return $this->arrayToXml->createXML('iati-organisations', $xmlData);
    }

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
        $xmlOrganization['recipient-country-budget'] = $this->recipientCountrybudgetElem->getXmlData($organizationData);
        $xmlOrganization['document-link']            = $this->documentLinkElem->getXmlData($organizationData);

        removeEmptyValues($xmlOrganization);

        return $xmlOrganization;
    }
}
