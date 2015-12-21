<?php namespace App\Core\V201\Element\Activity;

use App\Helpers\ArrayToXml;
use App\Models\Activity\Activity;
use App\Models\ActivityPublished;
use App\Models\Settings;
use DOMDocument;
use Illuminate\Support\Collection;

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
    protected $policyMakerElem;
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

    /**
     * @param ArrayToXml        $arrayToXml
     * @param ActivityPublished $activityPublished
     */
    public function __construct(ArrayToXml $arrayToXml, ActivityPublished $activityPublished)
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
        $this->policyMakerElem         = $activityElement->getPolicyMaker();
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
        $orgIdentifier     = $organization->reporting_org[0]['reporting_organization_identifier'];
        $filename          = sprintf('%s-%s.xml', $orgIdentifier, ($settings->publishing_type == "segmented") ? $this->segmentedXmlFile($activity) : 'activities');
        $publishedActivity = sprintf('%s-%s.xml', $orgIdentifier, $activity->activity_identifier);
        $xml               = $this->getXml($activity, $transaction, $result, $settings, $activityElement, $orgElem, $organization);
        $result            = $xml->save(public_path('uploads/files/activity/' . $publishedActivity));
        if ($result) {
            $publishedFiles = $this->savePublishedFiles($filename, $activity->organization_id, $publishedActivity);
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
            'last-updated-datetime' => gmdate('c', time($settings->updated_at)),
            'xml:lang'              => $settings->default_field_values[0]['default_language'],
            'default-currency'      => $settings->default_field_values[0]['default_currency']
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
        $xmlActivity['policy-marker']        = $this->policyMakerElem->getXmlData($activity);
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

        return array_filter(
            $xmlActivity,
            function ($value) {
                return $value;
            }
        );
    }

    /**
     * @param $published
     * @param $filename
     */
    public function getMergeXml($published, $filename)
    {
        $dom = new DOMDocument();
        $dom->appendChild($dom->createElement('iati-activities'));
        foreach ($published as $xml) {
            $addDom = new DOMDocument();
            $addDom->load(public_path('uploads/files/activity/' . $xml));
            if ($addDom->documentElement) {
                foreach ($addDom->documentElement->childNodes as $node) {
                    $dom->documentElement->appendChild(
                        $dom->importNode($node, true)
                    );
                }
            }
        }

        $dom->saveXml();
        $dom->save(public_path('uploads/files/activity/' . $filename));
    }

    /**
     * @param Activity $activity
     * @return string
     */
    public function segmentedXmlFile(Activity $activity)
    {
        $recipientCountry = (array) $activity['recipient_country'];
        $recipientRegion  = (array) $activity['recipient_region'];
        if (count($recipientRegion) == 1) {
            return $recipientRegion[0]['region_code'];
        } elseif (count($recipientCountry) == 1) {
            return $recipientCountry[0]['country_code'];
        } elseif (count($recipientCountry) > 1) {
            $maxPercentage = 0;
            $code          = $recipientCountry[0]['country_code'];
            foreach ($recipientCountry as $country) {
                $percentage = $country['percentage'];
                if ($percentage > $maxPercentage) {
                    $maxPercentage = $percentage;
                    $code          = $country['country_code'];
                }
            }

            return $code;
        } else {
            return '998';
        }
    }

    public function savePublishedFiles($filename, $organizationId, $publishedActivity)
    {
        $published = $this->activityPublished->firstOrNew(['filename' => $filename, 'organization_id' => $organizationId]);
        $published->touch();
        $publishedActivities = $publishedActivity;
        if (!is_array($publishedActivity)) {
            $publishedActivities = (array) $published->published_activities;
            (in_array($publishedActivity, $publishedActivities)) ?: array_push($publishedActivities, $publishedActivity);
        }
        $published->published_activities = $publishedActivities;
        $published->save();

        return $published->published_activities;
    }
}
