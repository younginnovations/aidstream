<?php namespace App\Core\V201\Element\Activity;

use App\Helpers\ArrayToXml;
use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Settings;
use DOMDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Class XmlGenerator
 * @package App\Core\V201\Element\Activity
 */
class XmlGenerator
{

    protected $titleElem;
    protected $arrayToXml;
    protected $descriptionElem;
    protected $activityStatusElem;
    protected $activityDateElem;
    protected $contactElem;
    protected $activityScopeElem;
    protected $participatingOrgElem;
    protected $recipientCountryElem;
    protected $recipientRegionElem;
    protected $locationElem;
    protected $sectorElem;
    protected $countryBudgetItemElem;
    protected $policyMarkerElem;
    protected $collaborationTypeElem;
    protected $defaultFlowTypeElem;
    protected $defaultFinanceTypeElem;
    protected $defaultAidTypeElem;
    protected $defaultTiedStatusElem;
    protected $budgetElem;
    protected $plannedDisbursementElem;
    protected $capitalSpendElem;
    protected $documentLinkElem;
    protected $relatedActivityElem;
    protected $legacyDataElem;
    protected $conditionElem;
    protected $transactionElem;
    protected $resultElem;
    protected $reportingOrgElem;
    protected $activityElement;
    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @param ArrayToXml        $arrayToXml
     * @param ActivityPublished $activityPublished
     * @param Settings          $settings
     */
    public function __construct(ArrayToXml $arrayToXml, ActivityPublished $activityPublished, Settings $settings)
    {
        $this->arrayToXml        = $arrayToXml;
        $this->activityPublished = $activityPublished;
    }

    /**
     * @param $activityElement
     * @param $orgElem
     */
    public function setElements($activityElement, $orgElem)
    {
        $this->activityElement         = $activityElement;
        $this->titleElem               = $activityElement->getTitle();
        $this->descriptionElem         = $activityElement->getDescription();
        $this->activityStatusElem      = $activityElement->getActivityStatus();
        $this->activityDateElem        = $activityElement->getActivityDate();
        $this->contactElem             = $activityElement->getContactInfo();
        $this->activityScopeElem       = $activityElement->getActivityScope();
        $this->participatingOrgElem    = $activityElement->getParticipatingOrganization();
        $this->recipientCountryElem    = $activityElement->getRecipientCountry();
        $this->recipientRegionElem     = $activityElement->getRecipientRegion();
        $this->locationElem            = $activityElement->getLocation();
        $this->sectorElem              = $activityElement->getSector();
        $this->countryBudgetItemElem   = $activityElement->getCountryBudgetItem();
        $this->policyMarkerElem        = $activityElement->getPolicyMarker();
        $this->collaborationTypeElem   = $activityElement->getCollaborationType();
        $this->defaultFlowTypeElem     = $activityElement->getDefaultFlowType();
        $this->defaultFinanceTypeElem  = $activityElement->getDefaultFinanceType();
        $this->defaultAidTypeElem      = $activityElement->getDefaultAidType();
        $this->defaultTiedStatusElem   = $activityElement->getDefaultTiedStatus();
        $this->budgetElem              = $activityElement->getBudget();
        $this->plannedDisbursementElem = $activityElement->getPlannedDisbursement();
        $this->capitalSpendElem        = $activityElement->getCapitalSpend();
        $this->documentLinkElem        = $activityElement->getDocumentLink();
        $this->relatedActivityElem     = $activityElement->getRelatedActivity();
        $this->legacyDataElem          = $activityElement->getLegacyData();
        $this->conditionElem           = $activityElement->getCondition();
        $this->transactionElem         = $activityElement->getTransaction();
        $this->resultElem              = $activityElement->getResult();
        $this->reportingOrgElem        = $orgElem->getOrgReportingOrg();
    }

    /**
     * @param Activity   $activity
     * @param Collection $transaction
     * @param Collection $result
     * @param Settings   $settings
     * @param            $activityElement
     * @param            $orgElem
     * @param            $organization
     */
    public function generateXml(Activity $activity, Collection $transaction, Collection $result, Settings $settings, $activityElement, $orgElem, $organization)
    {
        $this->settings    = $settings;
        $publisherId       = $this->settings->registry_info[0]['publisher_id'];
        $filename          = sprintf('%s-%s.xml', $publisherId, ($settings->publishing_type == "segmented") ? $this->segmentedXmlFile($activity) : 'activities');
        $publishedActivity = sprintf('%s-%s.xml', $publisherId, $activity->id);
        $xml               = $this->getXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization);

        $result = Storage::put(sprintf('%s%s', config('filesystems.xml'), $publishedActivity), $xml->saveXML());

        if ($result) {
            $publishedFiles = ($settings->publishing_type != "segmented")
                ? $this->savePublishedFiles($filename, $activity->organization_id, $publishedActivity)
                : $this->saveSegmentedPublishedFiles($filename, $activity, $publishedActivity);

            $this->getMergeXml($publishedFiles, $filename);
        }
    }

    /**
     * @param Activity   $activity
     * @param Collection $transaction
     * @param Collection $result
     * @param Settings   $settings
     * @param            $activityElement
     * @param            $orgElem
     * @param            $organization
     * @return DOMDocument
     */
    public function getXml(Activity $activity, Collection $transaction, Collection $result, Settings $settings, $activityElement, $orgElem, $organization)
    {
        $this->setElements($activityElement, $orgElem);
        $xmlData                                 = [];
        $xmlData['@attributes']                  = [
            'version'            => $settings->version,
            'generated-datetime' => gmdate('c')
        ];
        $xmlData['iati-activity']                = $this->getXmlData($activity, $transaction, $result, $organization);
        $xmlData['iati-activity']['@attributes'] = [
            'last-updated-datetime' => gmdate('c', time($activity->updated_at)),
            'xml:lang'              => $activity->default_field_values[0]['default_language'],
            'default-currency'      => $activity->default_field_values[0]['default_currency'],
            'hierarchy'             => ($hierarchy = $activity->default_field_values[0]['default_hierarchy']) ? $hierarchy : 1,
            'linked-data-uri'       => $activity->default_field_values[0]['linked_data_uri']
        ];

        return $this->arrayToXml->createXML('iati-activities', $xmlData);
    }

    /**
     * @param Activity   $activity
     * @param Collection $transaction
     * @param Collection $result
     * @param            $organization
     * @return array
     */
    public function getXmlData(Activity $activity, Collection $transaction, Collection $result, $organization)
    {
        $xmlActivity                         = [];
        $xmlActivity['iati-identifier']      = $activity->identifier['iati_identifier_text'];
        $xmlActivity['reporting-org']        = $this->reportingOrgElem->getXmlData($organization);
        $xmlActivity['title']                = $this->titleElem->getXmlData($activity);
        $xmlActivity['description']          = $this->descriptionElem->getXmlData($activity);
        $xmlActivity['participating-org']    = $this->participatingOrgElem->getXmlData($activity);
        $xmlActivity['activity-status']      = $this->activityStatusElem->getXmlData($activity);
        $xmlActivity['activity-date']        = $this->activityDateElem->getXmlData($activity);
        $xmlActivity['contact-info']         = $this->contactElem->getXmlData($activity);
        $xmlActivity['activity-scope']       = $this->activityScopeElem->getXmlData($activity);
        $xmlActivity['recipient-country']    = $this->recipientCountryElem->getXmlData($activity);
        $xmlActivity['recipient-region']     = $this->recipientRegionElem->getXmlData($activity);
        $xmlActivity['location']             = $this->locationElem->getXmlData($activity);
        $xmlActivity['sector']               = $this->sectorElem->getXmlData($activity);
        $xmlActivity['country-budget-items'] = $this->countryBudgetItemElem->getXmlData($activity);
        $xmlActivity['policy-marker']        = $this->policyMarkerElem->getXmlData($activity);
        $xmlActivity['collaboration-type']   = $this->collaborationTypeElem->getXmlData($activity);
        $xmlActivity['default-flow-type']    = $this->defaultFlowTypeElem->getXmlData($activity);
        $xmlActivity['default-finance-type'] = $this->defaultFinanceTypeElem->getXmlData($activity);
        $xmlActivity['default-aid-type']     = $this->defaultAidTypeElem->getXmlData($activity);
        $xmlActivity['default-tied-status']  = $this->defaultTiedStatusElem->getXmlData($activity);
        $xmlActivity['budget']               = $this->budgetElem->getXmlData($activity);
        $xmlActivity['planned-disbursement'] = $this->plannedDisbursementElem->getXmlData($activity);
        $xmlActivity['capital-spend']        = $this->capitalSpendElem->getXmlData($activity);
        $xmlActivity['transaction']          = $this->transactionElem->getXmlData($transaction);
        $xmlActivity['document-link']        = $this->documentLinkElem->getXmlData($activity);
        $xmlActivity['related-activity']     = $this->relatedActivityElem->getXmlData($activity);
        $xmlActivity['legacy-data']          = $this->legacyDataElem->getXmlData($activity);
        $xmlActivity['conditions']           = $this->conditionElem->getXmlData($activity);
        $xmlActivity['result']               = $this->resultElem->getXmlData($result);

        removeEmptyValues($xmlActivity);

        return $xmlActivity;
    }

    /**
     * @param $published
     * @param $filename
     */
    public function getMergeXml($published, $filename)
    {
        $dom            = new DOMDocument();
        $iatiActivities = $dom->appendChild($dom->createElement('iati-activities'));
        $iatiActivities->setAttribute('version', session('current_version'));
        $iatiActivities->setAttribute('generated-datetime', gmdate('c'));
        $iatiActivities->appendChild($dom->createTextNode("\n"));
        $iatiActivities->appendChild($dom->createComment("Generated By AidStream"));

        foreach ($published as $xml) {
            $addDom = new DOMDocument();
            $file   = sprintf("%s%s", public_path('files') . config('filesystems.xml'), $xml);
            $addDom->load($file);
            if ($addDom->documentElement) {
                foreach ($addDom->documentElement->childNodes as $node) {
                    $dom->documentElement->appendChild(
                        $dom->importNode($node, true)
                    );
                }
            }
        }

        $filePath = sprintf('%s%s%s', public_path('files'), config('filesystems.xml'), $filename);

        $this->saveXMLFile($filePath, $dom);
    }

    /**
     * @param Activity $activity
     * @return string
     */
    public function segmentedXmlFile(Activity $activity)
    {
        $recipientCountry = (array) $activity['recipient_country'];
        $recipientRegion  = (array) $activity['recipient_region'];

        if (count($recipientRegion) == 1 && $this->isEmpty($recipientRegion, 'region_code')) {
            return $recipientRegion[0]['region_code'];
        } elseif (count($recipientCountry) == 1 && $this->isEmpty($recipientCountry, 'country_code')) {
            return strtolower($recipientCountry[0]['country_code']);
        } elseif (count($recipientCountry) >= 1) {
            $maxPercentage = 0;
            $code          = strtolower($recipientCountry[0]['country_code']);
            foreach ($recipientCountry as $country) {
                $percentage = $country['percentage'];
                if ($percentage > $maxPercentage) {
                    $maxPercentage = $percentage;
                    $code          = strtolower($country['country_code']);
                }
            }

            return $code;
        }

        return '998';
    }

    /**
     * @param $filename
     * @param $organizationId
     * @param $publishedActivity
     * @return array
     */
    public function savePublishedFiles($filename, $organizationId, $publishedActivity)
    {
        $published = $this->activityPublished->firstOrNew(['filename' => $filename, 'organization_id' => $organizationId]);
        $published->touch();
        $publishedActivities = $publishedActivity;

        if (!is_array($publishedActivity)) {
            $publishedActivities = (array) $published->published_activities;
            (in_array($publishedActivity, $publishedActivities)) ?: array_push($publishedActivities, $publishedActivity);
        }

        $published->published_activities = array_unique($publishedActivities);
        $published->save();

        return $published->published_activities;
    }

    /**
     * Check if an array is empty at the provided $key.
     * @param array $data
     * @param       $key
     * @return bool
     */
    protected function isEmpty(array $data, $key)
    {
        $isEmpty = false;

        foreach ($data as $index => $item) {
            $isEmpty = empty(getVal($item, [$key], ''));
        }

        return $isEmpty;
    }

    /**
     * Save or Update published files for Organizations with segmented publishing type.
     * @param          $filename
     * @param Activity $activity
     * @param          $publishedActivity
     * @return array
     */
    protected function saveSegmentedPublishedFiles($filename, Activity $activity, $publishedActivity)
    {
        $activityXml                = [];
        $organizationId             = $activity->organization_id;
        $publishedActivities        = $this->activityPublished->where('organization_id', '=', $organizationId)->get();
        $activityForSameCountryCode = $this->activityPublished->where('filename', '=', $filename)
                                                              ->where('organization_id', '=', $organizationId)
                                                              ->first();
        $newActivity                = null;

        if ($activityForSameCountryCode) {
            foreach ($publishedActivities as $activityPublished) {
                if ($activityPublished->filename == $filename) {
                    $activityXml   = $activityForSameCountryCode->published_activities;
                    $activityXml[] = $publishedActivity;
                    $newActivity   = $activityPublished;
                }
            }

            $newActivity->published_activities = array_unique($activityXml);
            $newActivity->save();

            return $newActivity->published_activities;
        }

        foreach ($publishedActivities as $publishedActivityRow) {
            $publishedActivityColumn = $publishedActivityRow->published_activities ? $publishedActivityRow->published_activities : [];

            if (in_array($publishedActivity, $publishedActivityColumn)) {
                unset($publishedActivityColumn[array_search($publishedActivity, $publishedActivityColumn)]);
                $publishedActivityRow->published_activities = $publishedActivityColumn;
                $publishedActivityRow->save();
            }
        }

        $newActivity = $this->activityPublished->create(['published_activities' => [$publishedActivity], 'organization_id' => $organizationId, 'filename' => $filename]);

        return $newActivity->published_activities;
    }

    public function generateTemporaryXml(Activity $activity, Collection $transaction, Collection $result, Settings $settings, $activityElement, $orgElem, $organization)
    {
        $xml = $this->getXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization);

        return $xml->saveXML();
    }

    protected function saveXMLFile($filePath, $dom)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        file_put_contents($filePath, $dom->saveXML());
        chmod($filePath, 00777);
    }

    /**
     * @param Activity   $activity
     * @param Collection $transaction
     * @param Collection $result
     * @param Settings   $settings
     * @param            $activityElement
     * @param            $orgElem
     * @param            $organization
     */
    public function generateActivityXml(Activity $activity, Collection $transaction, Collection $result, Settings $settings, $activityElement, $orgElem, $organization)
    {
        $this->settings    = $settings;
        $publisherId       = $this->settings->registry_info[0]['publisher_id'];
        $filename          = sprintf('%s-%s.xml', $publisherId, ($settings->publishing_type == "segmented") ? $this->segmentedXmlFile($activity) : 'activities');
        $publishedActivity = sprintf('%s-%s.xml', $publisherId, $activity->id);
        $xml               = $this->getXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization);

        $result = Storage::put(sprintf('%s%s', config('filesystems.xml'), $publishedActivity), $xml->saveXML());

        if ($result) {
            $publishedFiles = ($settings->publishing_type != "segmented")
                ? $this->savePublishedFiles($filename, $activity->organization_id, $publishedActivity)
                : $this->saveSegmentedPublishedFiles($filename, $activity, $publishedActivity);

            $this->getMergeXml($publishedFiles, $filename);
        }
    }
}
