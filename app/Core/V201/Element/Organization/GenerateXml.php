<?php namespace App\Core\V201\Element\Organization;

use App\Core\V201\Repositories\Organization\NameRepository;
use app\Core\V201\Repositories\Organization\OrganizationRepository;
use App\Core\V201\Repositories\SettingsRepository;
use App\Helpers\ArrayToXml;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\Organization\OrganizationPublished;
use App\Models\Settings;

class GenerateXml {

    protected $arrayToXml;
    protected $organizationRepo;
    protected $nameRepo;
    protected $settingsRepo;
    protected $nameElem;
    protected $reportingOrgElem;
    protected $totalBudgetElem;
    protected $recipientOrgBudgetElem;
    protected $recipientCountrybudgetElem;
    protected $documentLinkElem;

    public function __construct(
        ArrayToXml $arrayToXml,
        OrganizationRepository $organizationRepo,
        NameRepository $nameRepo,
        SettingsRepository $settingsRepo,
        Name $nameElem,
        OrgReportingOrg $reportingOrgElem,
        TotalBudget $totalBudgetElem,
        RecipientOrgBudget $recipientOrgBudgetElem,
        RecipientCountryBudget $recipientCountrybudgetElem,
        DocumentLink $documentLinkElem,
        OrganizationPublished $organizationPublished
    ) {
        $this->arrayToXml = $arrayToXml;
        $this->organizationRepo = $organizationRepo;
        $this->nameRepo = $nameRepo;
        $this->settingsRepo = $settingsRepo;
        $this->nameElem = $nameElem;
        $this->reportingOrgElem = $reportingOrgElem;
        $this->totalBudgetElem = $totalBudgetElem;
        $this->recipientOrgBudgetElem = $recipientOrgBudgetElem;
        $this->recipientCountrybudgetElem = $recipientCountrybudgetElem;
        $this->documentLinkElem = $documentLinkElem;
        $this->organizationPublished = $organizationPublished;
    }

    /**
     * @param $id
     */
    public function generate($id) {

        $organization = $this->organizationRepo->getOrganization($id);
        $organizationData = $this->nameRepo->getOrganizationData($id);
        $settings = $this->settingsRepo->getSettings($id);
        $this->generateXmlFile($organization, $organizationData, $settings);

    }

    /**
     * @param Organization $organization
     * @param OrganizationData $organizationData
     * @param Settings $settings
     */
    public function generateXmlFile(Organization $organization, OrganizationData $organizationData, Settings $settings)
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
        $result = $xml->save(public_path('uploads/files/organization/' . $filename));
        if($result) {
            $published = $this->organizationPublished->firstOrNew(['filename' => $filename, 'organization_id' =>$organization->id]);
            if($published->exists) {
                $published->touch();
            } else {
                $published->filename = $filename;
                $published->organization_id = $organization->id;
                $published->save();
            }
        }
    }

    /**
     * @param Organization $organization
     * @param OrganizationData $organizationData
     * @return array
     */
    public function getXmlData (Organization $organization, OrganizationData $organizationData) {
        $xmlOrganization = [];
        $orgIdentifier = $organization->buildOrgReportingOrg()[0]['reporting_organization_identifier'];
        $name = $this->nameElem->getXmlData($organizationData);
        $reportingOrg = $this->reportingOrgElem->getXmlData($organization);
        $totalBudget = $this->totalBudgetElem->getXmlData($organizationData);
        $recipientOrgBudget = $this->recipientOrgBudgetElem->getXmlData($organizationData);
        $recipientCountryBudget = $this->recipientCountrybudgetElem->getXmlData($organizationData);
        $documentLink = $this->documentLinkElem->getXmlData($organizationData);
        $xmlOrganization['organisation-identifier'] = $orgIdentifier;
        if($name)
            $xmlOrganization['name'] = $name;
        if($reportingOrg)
            $xmlOrganization['reporting-org'] = $reportingOrg;
        if($totalBudget)
            $xmlOrganization['total-budget'] = $totalBudget;
        if($recipientOrgBudget)
            $xmlOrganization['recipient-org-budget'] = $recipientOrgBudget;
        if($recipientCountryBudget)
            $xmlOrganization['recipient-country-budget'] = $recipientCountryBudget;
        if($documentLink)
            $xmlOrganization['document-link'] = $documentLink;

        return $xmlOrganization;
    }

}