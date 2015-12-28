<?php namespace App\Core\V202\Xml\Activity;

use App\Core\V201\Element\Activity\XmlGenerator as XmlGenerator201;
use App\Models\Activity\Activity;
use Illuminate\Support\Collection;

/**
 * Class XmlGenerator
 * @package App\Core\V202\Element\Activity
 */
class XmlGenerator extends XmlGenerator201
{
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
        $xmlActivity['humanitarian-scope']   = $this->activityElement->getHumanitarianScopeXml($activity);
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
}
