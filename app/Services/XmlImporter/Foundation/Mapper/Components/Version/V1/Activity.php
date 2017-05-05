<?php namespace App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1;


use App\Services\XmlImporter\Foundation\Support\Helpers\Traits\XmlHelper;

/**
 * Class Activity
 * @package App\Services\XmlImporter\Foundation\Mapper\Components\Version\V1
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
    protected $activity = [];

    /**
     * @var array
     */
    public $identifier = [];

    /**
     * @var array
     */
    protected $otherIdentifier = [];

    /**
     * @var array
     */
    protected $title = [];

    /**
     * @var array
     */
    protected $reporting = [];

    /**
     * @var
     */
    public $orgRef;
    /**
     * @var array
     */
    protected $description = [];

    /**
     * @var array
     */
    protected $participatingOrg = [];

    /**
     * @var array
     */
    protected $activityDate = [];

    /**
     * @var array
     */
    protected $contactInfo = [];

    /**
     * @var array
     */
    protected $sector = [];

    /**
     * @var array
     */
    protected $budget = [];

    /**
     * @var array
     */
    protected $recipientRegion = [];

    /**
     * @var array
     */
    protected $recipientCountry = [];

    /**
     * @var array
     */
    protected $location = [];

    /**
     * @var array
     */
    protected $plannedDisbursement = [];

    /**
     * @var array
     */
    protected $capitalSpend = [];

    /**
     * @var array
     */
    protected $countryBudgetItems = [];

    /**
     * @var array
     */
    protected $documentLink = [];

    /**
     * @var array
     */
    protected $policyMarker = [];

    /**
     * @var array
     */
    protected $conditions = [];

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

    protected $unmappedSectorVocabulary = [];
    protected $unmappedPolicyMarkerVocabulary = [];

    /**
     * @var array
     */
    protected $emptyNarrative = [['narrative' => '', 'language' => '']];

    /**
     * @param $activity
     * @param $template
     * @return array
     */
    public function map($activity, $template)
    {
        foreach ($activity as $index => $element) {
            $elementName = $this->name($element);
            $this->resetIndex($elementName);
            $this->activity[$this->activityElements[$elementName]] = $this->$elementName($element, $template);
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
        if (!array_key_exists($this->activityElements[$elementName], $this->activity)) {
            $this->index = 0;
        }
    }

    public function reportingOrg($element, $template)
    {
        if (empty($this->identifier)) {
            $this->orgRef = $this->attributes($element, 'ref');
        } else {
            $this->identifier['activity_identifier'] = substr($this->identifier['iati_identifier_text'], strlen($this->attributes($element, 'ref')) + 1);
        }

        return $this->identifier;
    }

    public function iatiIdentifier($element, $template)
    {
        $this->identifier                         = $template['identifier'];
        $this->identifier['iati_identifier_text'] = $this->value($element);
        if ($this->orgRef) {
            $this->identifier['activity_identifier'] = substr($this->identifier['iati_identifier_text'], strlen($this->orgRef) + 1);
        }

        return $this->identifier;
    }

    public function otherIdentifier($element, $template)
    {
        $this->otherIdentifier[$this->index]                              = $template['other_identifier'];
        $this->otherIdentifier[$this->index]['reference']                 = $this->value($element);
        $this->otherIdentifier[$this->index]['owner_org'][0]['reference'] = $this->attributes($element, 'owner-ref');
        $this->otherIdentifier[$this->index]['owner_org'][0]['narrative'] = [['narrative' => $this->attributes($element, 'owner-name'), 'language' => '']];
        $this->index ++;

        return $this->otherIdentifier;
    }

    protected function activityWebsite($element, $template)
    {
        return null;
    }

    public function title($element, $template)
    {
        $this->title[$this->index] = $template['title'];
        foreach ($this->narrative($element) as $narrative) {
            $this->title[$this->index] = $narrative;
        }
        $this->index ++;

        return $this->title;
    }

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

    protected function activityStatus($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    public function activityDate($element, $template)
    {
        $this->activityDate[$this->index]              = $template['activity_date'];
        $this->activityDate[$this->index]['date']      = $this->attributes($element, 'iso-date');
        $this->activityDate[$this->index]['type']      = $this->getActivityDateType($this->attributes($element, 'type'));
        $this->activityDate[$this->index]['narrative'] = $this->narrative($element);
        $this->index ++;

        return $this->activityDate;
    }

    public function contactInfo($element, $template)
    {
        $this->contactInfo[$this->index]                                    = $template['contact_info'];
        $this->contactInfo[$this->index]['type']                            = $this->attributes($element, 'type');
        $this->contactInfo[$this->index]['organization'][0]['narrative']    = $this->groupNarrative(getVal($element, ['value'], []), 'organisation');
        $this->contactInfo[$this->index]['department'][0]['narrative']      = $this->groupNarrative(getVal($element, ['value'], []), 'department');
        $this->contactInfo[$this->index]['person_name'][0]['narrative']     = $this->groupNarrative(getVal($element, ['value'], []), 'personName');
        $this->contactInfo[$this->index]['job_title'][0]['narrative']       = $this->groupNarrative(getVal($element, ['value'], []), 'jobTitle');
        $this->contactInfo[$this->index]['telephone']                       = $this->filterValues(getVal($element, ['value'], []), 'telephone');
        $this->contactInfo[$this->index]['email']                           = $this->filterValues(getVal($element, ['value'], []), 'email');
        $this->contactInfo[$this->index]['website']                         = $this->filterValues(getVal($element, ['value'], []), 'website');
        $this->contactInfo[$this->index]['mailing_address'][0]['narrative'] = $this->groupNarrative(getVal($element, ['value'], []), 'mailingAddress');
        $this->index ++;

        return $this->contactInfo;
    }

    public function participatingOrg($element, $template)
    {
        $this->participatingOrg[$this->index]                      = $template['participating_organization'];
        $this->participatingOrg[$this->index]['organization_role'] = $this->getOrganisationRole($this->attributes($element, 'role'));
        $this->participatingOrg[$this->index]['identifier']        = $this->attributes($element, 'ref');
        $this->participatingOrg[$this->index]['organization_type'] = $this->attributes($element, 'type');
        $this->participatingOrg[$this->index]['activity_id']       = $this->attributes($element, 'activity-id');
        $this->participatingOrg[$this->index]['narrative']         = $this->narrative($element);
        $this->index ++;

        return $this->participatingOrg;
    }

    public function activityScope($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    public function recipientCountry($element, $template)
    {
        $this->recipientCountry[$this->index]                 = $template['recipient_country'];
        $this->recipientCountry[$this->index]['country_code'] = $this->attributes($element, 'code');
        $this->recipientCountry[$this->index]['percentage']   = $this->attributes($element, 'percentage');
        $this->recipientCountry[$this->index]['narrative']    = $this->narrative($element);
        $this->index ++;

        return $this->recipientCountry;
    }

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

    public function location($element, $template)
    {
        $this->location[$this->index]                                         = $template['location'];
        $this->location[$this->index]['name'][0]['narrative']                 = $this->groupNarrative($element['value'], 'name');
        $this->location[$this->index]['point'][0]['position'][0]['latitude']  = $this->attributes($element, 'latitude', 'coordinates');
        $this->location[$this->index]['point'][0]['position'][0]['longitude'] = $this->attributes($element, 'longitude', 'coordinates');
        $this->location[$this->index]['location_description'][0]['narrative'] = $this->groupNarrative($element['value'], 'description');
        $this->location[$this->index]['exactness'][0]['code']                 = $this->attributes($element, 'precision', 'coordinates');
        $this->index ++;

        return $this->location;
    }

    public function sector($element, $template)
    {
        $this->sector[$this->index]                         = $template['sector'];
        $vocabulary                                         = $this->getSectorVocabulary($this->attributes($element, 'vocabulary'));
        $this->sector[$this->index]['sector_vocabulary']    = $vocabulary;
        $this->sector[$this->index]['sector_text']          = ($vocabulary != 1 && $vocabulary != 2) ? $this->attributes($element, 'code') : '';
        $this->sector[$this->index]['sector_code']          = ($vocabulary == 1) ? $this->attributes($element, 'code') : '';
        $this->sector[$this->index]['sector_category_code'] = ($vocabulary == 2) ? $this->attributes($element, 'code') : '';
        $this->sector[$this->index]['percentage']           = $this->attributes($element, 'percentage');
        $this->sector[$this->index]['narrative']            = $this->narrative($element);
        $this->index ++;

        return $this->sector;
    }

    public function countryBudgetItems($element, $template)
    {
        $this->countryBudgetItems[$this->index]               = $template['country_budget_items'];
        $this->countryBudgetItems[$this->index]['vocabulary'] = $vocabulary = $this->attributes($element, 'vocabulary');
        foreach (getVal($element, ['value'], []) as $index => $budgetItem) {
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['code']                        = ($vocabulary == 1) ? $this->attributes($budgetItem, 'code') : "";
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['code_text']                   = ($vocabulary != 1) ? $this->attributes($budgetItem, 'code') : "";
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['percentage']                  = $this->attributes($budgetItem, 'percentage');
            $this->countryBudgetItems[$this->index]['budget_item'][$index]['description'][0]['narrative'] = (($desc = $this->groupNarrative(
                    getVal($budgetItem, ['value'], []),
                    'description'
                )) == '') ? $this->emptyNarrative : $desc;
        }
        $this->index ++;

        return $this->countryBudgetItems;
    }

    public function policyMarker($element, $template)
    {
        $this->policyMarker[$this->index]                  = $template['policy_marker'];
        $this->policyMarker[$this->index]['vocabulary']    = $this->getPolicyMarkerVocabulary($this->attributes($element, 'vocabulary'));
        $this->policyMarker[$this->index]['significance']  = $this->attributes($element, 'significance');
        $this->policyMarker[$this->index]['policy_marker'] = str_replace('0', '', $this->attributes($element, 'code'));
        $this->policyMarker[$this->index]['narrative']     = $this->narrative($element);
        $this->index ++;

        return $this->policyMarker;
    }

    public function collaborationType($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    protected function defaultFinanceType($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    protected function defaultFlowType($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    protected function defaultAidType($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    protected function defaultTiedStatus($element, $template)
    {
        return $this->attributes($element, 'code');
    }

    public function budget($element, $template)
    {
        $this->budget[$this->index]                            = $template['budget'];
        $this->budget[$this->index]['budget_type']             = $this->attributes($element, 'type');
        $this->budget[$this->index]['period_start'][0]['date'] = $this->attributes($element, 'iso-date', 'periodStart');
        $this->budget[$this->index]['period_end'][0]['date']   = $this->attributes($element, 'iso-date', 'periodEnd');
        $this->budget[$this->index]['value'][0]['amount']      = $this->value(getVal($element, ['value'], []), 'value');
        $this->budget[$this->index]['value'][0]['currency']    = $this->attributes($element, 'currency', 'value');
        $this->budget[$this->index]['value'][0]['value_date']  = $this->attributes($element, 'value-date', 'value');
        $this->index ++;

        return $this->budget;
    }

    public function plannedDisbursement($element, $template)
    {
        $this->plannedDisbursement[$this->index]                            = $template['planned_disbursement'];
        $this->plannedDisbursement[$this->index]['period_start'][0]['date'] = $this->attributes($element, 'iso-date', 'periodStart');
        $this->plannedDisbursement[$this->index]['period_end'][0]['date']   = $this->attributes($element, 'iso-date', 'periodEnd');
        $this->plannedDisbursement[$this->index]['value'][0]['amount']      = $this->value(getVal($element, ['value'], []), 'value');
        $this->plannedDisbursement[$this->index]['value'][0]['currency']    = $this->attributes($element, 'currency', 'value');
        $this->plannedDisbursement[$this->index]['value'][0]['value_date']  = $this->attributes($element, 'value-date', 'value');
        $this->index ++;

        return $this->plannedDisbursement;
    }

    public function capitalSpend($element, $template)
    {
        return $this->attributes($element, 'percentage');
    }

    public function documentLink($element, $template)
    {
        $this->documentLink[$this->index]                          = $template['document_link'];
        $this->documentLink[$this->index]['url']                   = $this->attributes($element, 'url');
        $this->documentLink[$this->index]['format']                = $this->attributes($element, 'format');
        $this->documentLink[$this->index]['title'][0]['narrative'] = $this->groupNarrative($element['value'], 'title');
        $this->documentLink[$this->index]['category']              = $this->filterAttributes($element['value'], 'category', ['code']);
        foreach ($this->filterAttributes($element['value'], 'language', ['code']) as $index => $language) {
            $this->documentLink[$this->index]['language'][$index]['language'] = $language['code'];
        }
        $this->documentLink[$this->index]['document_date'][0]['date'] = $this->attributes($element, 'iso-date', 'documentDate');
        $this->index ++;

        return $this->documentLink;
    }

    public function relatedActivity($element, $template)
    {
        $this->relatedActivity[$this->index]                        = $template['related_activity'];
        $this->relatedActivity[$this->index]['relationship_type']   = $this->attributes($element, 'type');
        $this->relatedActivity[$this->index]['activity_identifier'] = $this->attributes($element, 'ref');
        $this->index ++;

        return $this->relatedActivity;
    }

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

    public function legacyData($activity, $template)
    {
        $this->legacyData[$this->index]                    = $template['legacy_data'];
        $this->legacyData[$this->index]['name']            = $this->attributes($activity, 'name');
        $this->legacyData[$this->index]['value']           = $this->attributes($activity, 'value');
        $this->legacyData[$this->index]['iati_equivalent'] = $this->attributes($activity, 'iati-equivalent');
        $this->index ++;

        return $this->legacyData;
    }

    protected function getPolicyMarkerVocabulary($vocabulary)
    {
        switch ($vocabulary) {
            case 'DAC':
                return '1';
            case 'RO':
                return '99';
            default:
                ($vocabulary == "") ?: $this->unmappedPolicyMarkerVocabulary[] = $vocabulary;

                return '';
        }
    }

    protected function getSectorVocabulary($vocabulary)
    {
        switch ($vocabulary) {
            case 'DAC' :
                return '1';
            case 'DAC-3' :
                return '2';
            case 'COFOG':
                return '3';
            case 'NTEE' :
                return '5';
            case 'RO' :
                return '99';
            default:
                ($vocabulary == "") ?: $this->unmappedSectorVocabulary[] = $vocabulary;

                return '';
        }
    }

    protected function getOrganisationRole($type)
    {
        switch (ucwords($type)) {
            case 'Funding':
                return '1';
            case 'Accountable':
                return '2';
            case 'Extending' :
                return '3';
            case 'Implementing' :
                return '4';
            default:
                return '';
        }
    }

    protected function getActivityDateType($type)
    {
        switch ($type) {
            case 'start-planned':
                return '1';
            case 'start-actual':
                return '2';
            case 'end-planned' :
                return '3';
            case 'end-actual' :
                return '4';
            default:
                return '';
        }
    }
}
