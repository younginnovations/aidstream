<?php namespace App\Core\V201\Element\Organization;

use App\Helpers\ArrayToXml;
use App\Models\Organization\Organization;
use App\Models\Organization\OrganizationData;
use App\Models\Settings;
use App\Models\OrganizationPublished;
use App\Services\Organization\OrganizationManager;
use App\Services\Publisher\Publisher;
use App\Services\Workflow\Traits\ExceptionParser;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Psr\Log\LoggerInterface;

/**
 * Class XmlGenerator
 * @package app\Core\V201\Element\Organization
 */
class XmlGenerator
{
    use ExceptionParser;

    /**
     * Error code when package is not found.
     */
    const  PACKAGE_NOT_FOUND_ERROR_CODE = 404;
    /**
     * Error code when user is not authorized to perform the api action.
     */
    const  NOT_AUTHORIZED_ERROR_CODE = 403;
    /**
     * @var ArrayToXml
     */
    protected $arrayToXml;
    /**
     * @var
     */
    protected $orgElem;
    /**
     * @var
     */
    protected $nameElem;
    /**
     * @var
     */
    protected $reportingOrgElem;
    /**
     * @var
     */
    protected $totalBudgetElem;
    /**
     * @var
     */
    protected $recipientOrgBudgetElem;
    /**
     * @var
     */
    protected $recipientCountrybudgetElem;
    /**
     * @var
     */
    protected $documentLinkElem;
    /**
     * @var OrganizationManager
     */
    protected $organizationManager;
    /**
     * @var OrganizationPublished
     */
    protected $organizationPublished;
    /**
     * @var Publisher
     */
    private $publisher;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ArrayToXml            $arrayToXml
     * @param OrganizationPublished $organizationPublished
     * @param OrganizationManager   $organizationManager
     * @param Publisher             $publisher
     * @param LoggerInterface       $logger
     */
    public function __construct(ArrayToXml $arrayToXml, OrganizationPublished $organizationPublished, OrganizationManager $organizationManager, Publisher $publisher, LoggerInterface $logger)
    {
        $this->arrayToXml            = $arrayToXml;
        $this->organizationPublished = $organizationPublished;
        $this->organizationManager   = $organizationManager;
        $this->publisher             = $publisher;
        $this->logger                = $logger;
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
        try {
            $publisherId    = array_get($settings, 'registry_info.0.publisher_id');
            $currentOrgData = $organization->orgData()->where('status', '=', 3)->where('is_reporting_org',true)->get();
            if ($organizationData->status == '2') {
                $currentOrgData->push($organizationData);
            }

            $xml = $this->getTotalXml($organization, $currentOrgData, $settings, $orgElem);

            $filename = $publisherId . '-org.xml';

            $result = Storage::put(sprintf('%s%s', config('filesystems.xml'), $filename), $xml->saveXML());

            if ($result) {
                $published = $this->organizationPublished->firstOrNew(['filename' => $filename, 'organization_id' => $organization->id]);
                $published->touch();
                $published->filename        = $filename;
                $published->organization_id = $organization->id;

                if (null === $published->published_org_data) {
                    $published->published_org_data = [$organizationData->id];
                } else {
                    $orgDataIds = $published->published_org_data;
                    if (!in_array($organizationData->id, $orgDataIds)) {
                        $orgDataIds[] = $organizationData->id;
                    }
                    $published->published_org_data = $orgDataIds;

                }
                $published->save();
            }

            if (getVal($settings->toArray(), ['registry_info', 0, 'publish_files']) == 'yes') {
                $this->publisher->publishFile(getVal($settings->toArray(), ['registry_info'], []), $published, $organization, getVal($settings->toArray(), ['publishing_type']));
            }

            return ['status' => true];

        } catch (Exception $exception) {
            $this->logger->error($exception, ['trace' => $exception->getTraceAsString()]);

            return $this->parse($exception);
        }
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
            'default-currency'      => $settings->default_field_values[0]['default_currency'],
            'xmlns:aidstream'       => 'http://example.org/aidstream/ns#'
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
        $xmlOrganization['organisation-identifier']  = $organizationData->is_reporting_org ? $organization->reporting_org[0]['reporting_organization_identifier'] : $organizationData->identifier;
        $xmlOrganization['name']                     = $this->nameElem->getXmlData($organizationData);
        $xmlOrganization['reporting-org']            = $this->reportingOrgElem->getXmlData($organization);
        $xmlOrganization['total-budget']             = $this->totalBudgetElem->getXmlData($organizationData);
        $xmlOrganization['recipient-org-budget']     = $this->recipientOrgBudgetElem->getXmlData($organizationData);
        $xmlOrganization['recipient-country-budget'] = $this->recipientCountrybudgetElem->getXmlData($organizationData);
        $xmlOrganization['document-link']            = $this->documentLinkElem->getXmlData($organizationData);
        $xmlOrganization['aidstream:type']           = $organizationData->type;
        $xmlOrganization['aidstream:country']        = $organizationData->country;

        removeEmptyValues($xmlOrganization);

        return $xmlOrganization;
    }

    /**
     * @param Organization     $organization
     * @param OrganizationData $organizationData
     * @param Settings         $settings
     * @param                  $orgElem
     * @return string
     */
    public function generateTemporaryXml(Organization $organization, OrganizationData $organizationData, Settings $settings, $orgElem)
    {
        $xml = $this->getXml($organization, $organizationData, $settings, $orgElem);

        return $xml->saveXML();
    }

    /**
     * Generate xml for multiple OrganizationData.
     *
     * @param            $organization
     * @param Collection $currentOrgData
     * @param            $settings
     * @param            $orgElem
     * @return \DomDocument
     */
    protected function getTotalXml($organization, Collection $currentOrgData, $settings, $orgElem)
    {
        $this->setElements($orgElem);
        $xmlData                = [];
        $xmlData['@attributes'] = [
            'version'            => $settings->version,
            'generated-datetime' => gmdate('c')
        ];

        foreach ($currentOrgData as $index => $organizationData) {
            $xmlData['iati-organisation'][$index]                = $this->getXmlData($organization, $organizationData);
            $xmlData['iati-organisation'][$index]['@attributes'] = [
                'last-updated-datetime' => gmdate('c', time($settings->updated_at)),
                'xml:lang'              => $settings->default_field_values[0]['default_language'],
                'default-currency'      => $settings->default_field_values[0]['default_currency'],
                'xmlns:aidstream'       => 'http://example.org/aidstream/ns#'
            ];
        }

        return $this->arrayToXml->createXML('iati-organisations', $xmlData);
    }
}
