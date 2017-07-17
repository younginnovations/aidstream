<?php namespace App\Services\XmlImporter\Foundation\Mapper\Components\Version\V2;

use App\Services\XmlImporter\Foundation\Support\Helpers\Traits\XmlHelper;

/**
 * Class Activity
 * @package App\Services\XmlImporter\Mapper\V103\Activity
 */
class Activity
{
    use XmlHelper;

    /**
     * @var array
     */
    protected $activityElements = [
        'iatiIdentifier'      => 'identifier',
        'otherIdentifier'     => 'other_identifier',
        'reportingOrg'        => 'identifier',
        'title'               => 'title',
        'description'         => 'description',
        'activityStatus'      => 'activity_status',
        'activityDate'        => 'activity_date',
        'contactInfo'         => 'contact_info',
        'activityScope'       => 'activity_scope',
        'participatingOrg'    => 'participating_organization',
        'recipientCountry'    => 'recipient_country',
        'recipientRegion'     => 'recipient_region',
        'location'            => 'location',
        'sector'              => 'sector',
        'countryBudgetItems'  => 'country_budget_items',
        'humanitarianScope'   => 'humanitarian_scope',
        'policyMarker'        => 'policy_marker',
        'collaborationType'   => 'collaboration_type',
        'defaultFlowType'     => 'default_flow_type',
        'defaultFinanceType'  => 'default_finance_type',
        'defaultAidType'      => 'default_aid_type',
        'defaultTiedStatus'   => 'default_tied_status',
        'budget'              => 'budget',
        'plannedDisbursement' => 'planned_disbursement',
        'capitalSpend'        => 'capital_spend',
        'documentLink'        => 'document_link',
        'relatedActivity'     => 'related_activity',
        'legacyData'          => 'legacy_data',
        'conditions'          => 'conditions',
        'defaultFieldValues'  => 'default_field_values'
    ];

    /**
     * @var array
     */
    public $activity = [];

    /**
     * @var array
     */
    public $identifier = [];

    /**
     * @var array
     */
    public $otherIdentifier = [];

    /**
     * @var array
     */
    public $title = [];

    /**
     * @var array
     */
    public $reporting = [];

    /**
     * @var
     */
    public $orgRef;
    /**
     * @var array
     */
    public $description = [];

    /**
     * @var array
     */
    public $participatingOrg = [];

    /**
     * @var array
     */
    public $activityDate = [];

    /**
     * @var array
     */
    public $contactInfo = [];

    /**
     * @var array
     */
    public $sector = [];

    /**
     * @var array
     */
    public $budget = [];

    /**
     * @var array
     */
    public $recipientRegion = [];

    /**
     * @var array
     */
    public $recipientCountry = [];

    /**
     * @var array
     */
    public $location = [];

    /**
     * @var array
     */
    public $plannedDisbursement = [];

    /**
     * @var array
     */
    public $capitalSpend = [];

    /**
     * @var array
     */
    public $countryBudgetItems = [];

    /**
     * @var array
     */
    public $documentLink = [];

    /**
     * @var array
     */
    public $policyMarker = [];

    /**
     * @var array
     */
    public $conditions = [];

    /**
     * @var array
     */
    protected $legacyData = [];

    /**
     * @var array
     */
    protected $humanitarianScope = [];

    /**
     * @var array
     */
    protected $relatedActivity = [];

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var array
     */
    protected $emptyNarrative = [['narrative' => '', 'language' => '']];

    /**
     * @param array $elementData
     * @param       $template
     * @return array
     */
    public function map($elementData = [], $template)
    {
        foreach ($elementData as $activityIndex => $element) {
            $elementName = $this->name($element);
            $this->resetIndex($elementName);
            if (array_key_exists($elementName, $this->activityElements)) {
                $this->activity[$this->activityElements[$elementName]] = $this->$elementName($element, $template);
            }
        }

        if (array_key_exists('description', $this->activity)) {
            $this->activity['description'] = array_values(getVal($this->activity, ['description'], null));
        }

        return $this->activity;
    }

    /**
     * @param $elementName
     */
    protected function resetIndex($elementName)
    {
        if ((!array_key_exists($this->activityElements[$elementName], $this->activity))) {
            $this->index = 0;
        }
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function iatiIdentifier($element, $template)
    {
        $this->identifier                         = $template['identifier'];
        $this->identifier['iati_identifier_text'] = $this->value($element);
        if ($this->orgRef) {
            $this->identifier['activity_identifier'] = substr($this->identifier['iati_identifier_text'], strlen($this->orgRef) + 1);
        }

        return $this->identifier;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function otherIdentifier($element, $template)
    {
        $this->otherIdentifier[$this->index]                              = $template['other_identifier'];
        $this->otherIdentifier[$this->index]['reference']                 = $this->attributes($element, 'ref');
        $this->otherIdentifier[$this->index]['type']                      = $this->attributes($element, 'type');
        $this->otherIdentifier[$this->index]['owner_org'][0]['reference'] = $this->attributes($element, 'ref', 'ownerOrg');
        $this->otherIdentifier[$this->index]['owner_org'][0]['narrative'] = (($narrative = $this->value(getVal($element, ['value'], []), 'ownerOrg')) == '') ? $this->emptyNarrative : $narrative;
        $this->index ++;

        return $this->otherIdentifier;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function title($element, $template)
    {
        foreach ($element['value'] as $index => $value) {
            $this->title = $template['title'];
            $this->title = $this->narrative($element);
        }

        return $this->title;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function reportingOrg($element, $template)
    {
        if (empty($this->identifier)) {
            $this->orgRef = $this->attributes($element, 'ref');
        } else {
            $this->identifier['activity_identifier'] = substr($this->identifier['iati_identifier_text'], strlen($this->attributes($element, 'ref')) + 1);
        }

        return $this->identifier;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function description($element, $template)
    {
        $type                                 = $this->attributes($element, 'type');
        $descType                             = ($type == '') ? 1 : $type;
        $this->description[$descType]['type'] = $descType;

        if (array_key_exists('narrative', getVal($this->description, [$descType], []))) {
            $narrativeIndex = count($this->description[$descType]['narrative']);
            foreach ($this->narrative($element) as $narrative) {
                $this->description[$descType]['narrative'][$narrativeIndex] = $narrative;
                $narrativeIndex ++;
            }
        } else {
            $this->description[$descType]['narrative'] = $this->narrative($element);
        }

        return $this->description;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function participatingOrg($element, $template)
    {
        $this->participatingOrg[$this->index]                      = $template['participating_organization'];
        $this->participatingOrg[$this->index]['organization_role'] = $this->attributes($element, 'role');
        $this->participatingOrg[$this->index]['identifier']        = $this->attributes($element, 'ref');
        $this->participatingOrg[$this->index]['organization_type'] = $this->attributes($element, 'type');
        $this->participatingOrg[$this->index]['activity_id']       = $this->attributes($element, 'activity-id');
        $this->participatingOrg[$this->index]['narrative']         = $this->narrative($element);
        $this->index ++;

        return $this->participatingOrg;
    }

    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function activityStatus($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function activityDate($element, $template)
    {
        $this->activityDate[$this->index]              = $template['activity_date'];
        $this->activityDate[$this->index]['date']      = dateFormat('Y-m-d', $this->attributes($element, 'iso-date'));
        $this->activityDate[$this->index]['type']      = $this->attributes($element, 'type');
        $this->activityDate[$this->index]['narrative'] = $this->narrative($element);
        $this->index ++;

        return $this->activityDate;
    }

    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function activityScope($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function contactInfo($element, $template)
    {
        $this->contactInfo[$this->index]                                    = $template['contact_info'];
        $this->contactInfo[$this->index]['type']                            = $this->attributes($element, 'type');
        $this->contactInfo[$this->index]['organization'][0]['narrative']    = $this->value(getVal($element, ['value'], []), 'organisation');
        $this->contactInfo[$this->index]['department'][0]['narrative']      = $this->value(getVal($element, ['value'], []), 'department');
        $this->contactInfo[$this->index]['person_name'][0]['narrative']     = $this->value(getVal($element, ['value'], []), 'personName');
        $this->contactInfo[$this->index]['job_title'][0]['narrative']       = $this->value(getVal($element, ['value'], []), 'jobTitle');
        $this->contactInfo[$this->index]['telephone']                       = $this->filterValues(getVal($element, ['value'], []), 'telephone');
        $this->contactInfo[$this->index]['email']                           = $this->filterValues(getVal($element, ['value'], []), 'email');
        $this->contactInfo[$this->index]['website']                         = $this->filterValues(getVal($element, ['value'], []), 'website');
        $this->contactInfo[$this->index]['mailing_address'][0]['narrative'] = $this->value(getVal($element, ['value'], []), 'mailingAddress');
        $this->index ++;

        return $this->contactInfo;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function sector($element, $template)
    {
        $this->sector[$this->index]                         = $template['sector'];
        $vocabulary                                         = $this->attributes($element, 'vocabulary');
        $this->sector[$this->index]['sector_vocabulary']    = $vocabulary;
        $this->sector[$this->index]['vocabulary_uri']       = $this->attributes($element, 'vocabulary_uri');
        $this->sector[$this->index]['sector_code']          = ($vocabulary == 1) ? $this->attributes($element, 'code') : '';
        $this->sector[$this->index]['sector_category_code'] = ($vocabulary == 2) ? $this->attributes($element, 'code') : '';
        $this->sector[$this->index]['sector_text']          = ($vocabulary != 1 && $vocabulary != 2) ? $this->attributes($element, 'code') : '';
        $this->sector[$this->index]['percentage']           = $this->attributes($element, 'percentage');
        $this->sector[$this->index]['narrative']            = $this->narrative($element);
        $this->index ++;

        return $this->sector;
    }

    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function defaultFlowType($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function defaultFinanceType($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function defaultAidType($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function defaultTiedStatus($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function budget($element, $template)
    {
        $this->budget[$this->index]                            = $template['budget'];
        $this->budget[$this->index]['budget_type']             = $this->attributes($element, 'type');
        $this->budget[$this->index]['status']                  = $this->attributes($element, 'status');
        $this->budget[$this->index]['period_start'][0]['date'] = dateFormat('Y-m-d', $this->attributes($element, 'iso-date', 'periodStart'));
        $this->budget[$this->index]['period_end'][0]['date']   = dateFormat('Y-m-d', $this->attributes($element, 'iso-date', 'periodEnd'));
        $this->budget[$this->index]['value'][0]['amount']      = $this->value(getVal($element, ['value'], []), 'value');
        $this->budget[$this->index]['value'][0]['currency']    = $this->attributes($element, 'currency', 'value');
        $this->budget[$this->index]['value'][0]['value_date']  = dateFormat('Y-m-d', $this->attributes($element, 'value-date', 'value'));
        $this->index ++;

        return $this->budget;
    }


    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function recipientRegion($element, $template)
    {
        $this->recipientRegion[$this->index]                      = $template['recipient_region'];
        $this->recipientRegion[$this->index]['region_code']       = $this->attributes($element, 'code');
        $this->recipientRegion[$this->index]['region_vocabulary'] = $this->attributes($element, 'vocabulary');
        $this->recipientRegion[$this->index]['vocabulary_uri']    = $this->attributes($element, 'vocabulary-uri');
        $this->recipientRegion[$this->index]['percentage']        = $this->attributes($element, 'percentage');
        $this->recipientRegion[$this->index]['narrative']         = $this->narrative($element);
        $this->index ++;

        return $this->recipientRegion;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function recipientCountry($element, $template)
    {
        $this->recipientCountry[$this->index]                 = $template['recipient_country'];
        $this->recipientCountry[$this->index]['country_code'] = $this->attributes($element, 'code');
        $this->recipientCountry[$this->index]['percentage']   = $this->attributes($element, 'percentage');
        $this->recipientCountry[$this->index]['narrative']    = $this->narrative($element);
        $this->index ++;

        return $this->recipientCountry;
    }


    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function location($element, $template)
    {
        $this->location[$this->index]                                         = $template['location'];
        $this->location[$this->index]['reference']                            = $this->attributes($element, 'ref');
        $this->location[$this->index]['location_reach'][0]['code']            = $this->attributes($element, 'code', 'locationReach');
        $this->location[$this->index]['location_id'][0]['vocabulary']         = $this->attributes($element, 'vocabulary', 'locationId');
        $this->location[$this->index]['location_id'][0]['code']               = $this->attributes($element, 'code', 'locationId');
        $this->location[$this->index]['name'][0]['narrative']                 = (($name = $this->value(getVal($element, ['value'], []), 'name')) == '') ? $this->emptyNarrative : $name;
        $this->location[$this->index]['location_description'][0]['narrative'] = (($locationDesc = $this->value(
                getVal($element, ['value'], []),
                'description'
            )) == '') ? $this->emptyNarrative : $locationDesc;
        $this->location[$this->index]['activity_description'][0]['narrative'] = (($elementDesc = $this->value(
                getVal($element, ['value'], []),
                'activityDescription'
            )) == '') ? $this->emptyNarrative : $elementDesc;
        $this->location[$this->index]['administrative']                       = $this->filterAttributes(getVal($element, ['value'], []), 'administrative', ['code', 'vocabulary', 'level']);
        $this->location[$this->index]['point'][0]['srs_name']                 = $this->attributes($element, 'srsName', 'point');
        $this->location[$this->index]['point'][0]['position'][0]              = $this->latAndLong(getVal($element, ['value'], []));
        $this->location[$this->index]['exactness'][0]['code']                 = $this->attributes($element, 'code', 'exactness');
        $this->location[$this->index]['location_class'][0]['code']            = $this->attributes($element, 'code', 'locationClass');
        $this->location[$this->index]['feature_designation'][0]['code']       = $this->attributes($element, 'code', 'featureDesignation');
        $this->index ++;

        return $this->location;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function plannedDisbursement($element, $template)
    {
        $this->plannedDisbursement[$this->index]                                   = $template['planned_disbursement'];
        $this->plannedDisbursement[$this->index]['planned_disbursement_type']      = $this->attributes($element, 'type');
        $this->plannedDisbursement[$this->index]['period_start'][0]['date']        = dateFormat('Y-m-d', $this->attributes($element, 'iso-date', 'periodStart'));
        $this->plannedDisbursement[$this->index]['period_end'][0]['date']          = dateFormat('Y-m-d', $this->attributes($element, 'iso-date', 'periodEnd'));
        $this->plannedDisbursement[$this->index]['value'][0]['amount']             = $this->value(getVal($element, ['value'], []), 'value');
        $this->plannedDisbursement[$this->index]['value'][0]['currency']           = $this->attributes($element, 'currency', 'value');
        $this->plannedDisbursement[$this->index]['value'][0]['value_date']         = dateFormat('Y-m-d', $this->attributes($element, 'value-date', 'value'));
        $this->plannedDisbursement[$this->index]['provider_org'][0]['ref']         = $this->attributes($element, 'ref', 'providerOrg');
        $this->plannedDisbursement[$this->index]['provider_org'][0]['activity_id'] = $this->attributes($element, 'provider-activity-id', 'providerOrg');
        $this->plannedDisbursement[$this->index]['provider_org'][0]['type']        = $this->attributes($element, 'type', 'providerOrg');
        $this->plannedDisbursement[$this->index]['provider_org'][0]['narrative']   = (($providerOrg = $this->value(
                getVal($element, ['value'], []),
                'providerOrg'
            )) == '') ? $this->emptyNarrative : $providerOrg;
        $this->plannedDisbursement[$this->index]['receiver_org'][0]['ref']         = $this->attributes($element, 'ref', 'receiverOrg');
        $this->plannedDisbursement[$this->index]['receiver_org'][0]['activity_id'] = $this->attributes($element, 'receiver-activity-id', 'receiverOrg');
        $this->plannedDisbursement[$this->index]['receiver_org'][0]['type']        = $this->attributes($element, 'type', 'receiverOrg');
        $this->plannedDisbursement[$this->index]['receiver_org'][0]['narrative']   = (($receiverOrg = $this->value(
                getVal($element, ['value'], []),
                'receiverOrg'
            )) == '') ? $this->emptyNarrative : $receiverOrg;
        $this->index ++;

        return $this->plannedDisbursement;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function countryBudgetItems($element, $template)
    {
        $this->countryBudgetItems[$this->index]               = $template['country_budget_items'];
        $this->countryBudgetItems[$this->index]['vocabulary'] = $vocabulary = $this->attributes($element, 'vocabulary');
        foreach (getVal($element, ['value'], []) as $index => $budgetItem) {
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['code']                        = ($vocabulary == 1) ? $this->attributes($budgetItem, 'code') : "";
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['code_text']                   = ($vocabulary != 1) ? $this->attributes($budgetItem, 'code') : "";
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['percentage']                  = $this->attributes($budgetItem, 'percentage');
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['description'][0]['narrative'] = (($desc = $this->value(
                    getVal($budgetItem, ['value'], []),
                    'description'
                )) == '') ? $this->emptyNarrative : $desc;
        }
        $this->index ++;

        return $this->countryBudgetItems;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function documentLink($element, $template)
    {
        $this->documentLink[$this->index]                          = $template['document_link'];
        $this->documentLink[$this->index]['url']                   = $this->attributes($element, 'url');
        $this->documentLink[$this->index]['format']                = $this->attributes($element, 'format');
        $this->documentLink[$this->index]['title'][0]['narrative'] = (($title = $this->value(getVal($element, ['value'], []), 'title')) == '') ? $this->emptyNarrative : $title;
        $this->documentLink[$this->index]['category']              = $this->filterAttributes($element['value'], 'category', ['code']);
        foreach ($this->filterAttributes($element['value'], 'language', ['code']) as $index => $language) {
            $this->documentLink[$this->index]['language'][$index]['language'] = $language['code'];
        }
        $this->documentLink[$this->index]['document_date'][0]['date'] = dateFormat('Y-m-d', $this->attributes($element, 'iso-date', 'documentDate'));
        $this->index ++;

        return $this->documentLink;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function policyMarker($element, $template)
    {
        $this->policyMarker[$this->index]                   = $template['policy_marker'];
        $this->policyMarker[$this->index]['vocabulary']     = $this->attributes($element, 'vocabulary');
        $this->policyMarker[$this->index]['vocabulary_uri'] = $this->attributes($element, 'vocabulary-uri');
        $this->policyMarker[$this->index]['policy_marker']  = $this->attributes($element, 'code');
        $this->policyMarker[$this->index]['significance']   = $this->attributes($element, 'significance');
        $this->policyMarker[$this->index]['narrative']      = $this->narrative($element);
        $this->index ++;

        return $this->policyMarker;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function conditions($element, $template)
    {
        $this->conditions                       = $template['conditions'];
        $this->conditions['condition_attached'] = $this->attributes($element, 'attached');
        foreach (getVal($element, ['value'], []) as $index => $condition) {
            $this->conditions['condition'][$index]['condition_type'] = $this->attributes($condition, 'type');
            $this->conditions['condition'][$index]['narrative']      = $this->narrative($condition);
        }
        $this->index ++;

        return $this->conditions;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function legacyData($element, $template)
    {
        $this->legacyData[$this->index]                    = $template['legacy_data'];
        $this->legacyData[$this->index]['name']            = $this->attributes($element, 'name');
        $this->legacyData[$this->index]['value']           = $this->attributes($element, 'value');
        $this->legacyData[$this->index]['iati_equivalent'] = $this->attributes($element, 'iati-equivalent');
        $this->index ++;

        return $this->legacyData;
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function humanitarianScope($element, $template)
    {
        $this->humanitarianScope[$this->index]                   = $template['humanitarian_scope'];
        $this->humanitarianScope[$this->index]['type']           = $this->attributes($element, 'type');
        $this->humanitarianScope[$this->index]['vocabulary']     = $this->attributes($element, 'vocabulary');
        $this->humanitarianScope[$this->index]['vocabulary_uri'] = $this->attributes($element, 'vocabulary-uri');
        $this->humanitarianScope[$this->index]['code']           = $this->attributes($element, 'code');
        $this->humanitarianScope[$this->index]['narrative']      = $this->narrative($element);
        $this->index ++;

        return $this->humanitarianScope;
    }

    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function collaborationType($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    /**
     * @param $element
     * @param $template
     * @return mixed|string
     */
    public function capitalSpend($element, $template)
    {
        return $this->attributes($element, 'percentage');
    }

    /**
     * @param $element
     * @param $template
     * @return array
     */
    public function relatedActivity($element, $template)
    {
        $this->relatedActivity[$this->index]                        = $template['related_activity'];
        $this->relatedActivity[$this->index]['relationship_type']   = $this->attributes($element, 'type');
        $this->relatedActivity[$this->index]['activity_identifier'] = $this->attributes($element, 'ref');
        $this->index ++;

        return $this->relatedActivity;
    }
}
