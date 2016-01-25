<?php namespace App\Core\V201\Formatter\Factory\Traits;


/**
 * Class ElementDataPacker
 *
 * Packs Element data for Complete Csv.
 * @package App\Core\V201\Formatter\Factory\Traits
 */
trait ElementDataPacker
{
    /**
     * Data holder for Narratives.
     * @var array
     */
    protected $narrativeDataHolder = ['narrative' => [], 'language' => []];

    /**
     * Concatenate the values in a specific $key of an $iterable with a ';'
     * @param array $iterable
     * @param       $key
     * @param null  $nested
     * @param null  $nestedKey
     * @return string
     */
    protected function concatenateIntoString(array $iterable = null, $key, $nested = null, $nestedKey = null)
    {
        if (!is_null($iterable)) {
            $placeholders = [];

            if (!$nested) {
                foreach ($iterable as $iterate) {
                    $placeholders[] = (is_array($iterate) && array_key_exists($key, $iterate)) ? $iterate[$key] : '';
                }
            } else {
                foreach ($iterable as $iterate) {
                    if (is_array($iterate) && array_key_exists($key, $iterate)) {
                        foreach ($iterate[$key] as $nest) {
                            $placeholders[] = (is_array($nest) && array_key_exists($nestedKey, $nest)) ? $nest[$nestedKey] : '';
                        }
                    }
                }
            }

            return implode(';', $placeholders);
        }
    }

    /**
     * Fill Condition data.
     * @param            $activityId
     * @param array      $data
     * @param array|null $conditions
     * @return array
     */
    protected function fillConditionData($activityId, array $data, array $conditions = null)
    {
        if (!is_null($conditions)) {
            $data[$activityId]['Activity_conditions_attached']                     = $conditions['condition_attached'];
            $data[$activityId]['Activity_conditions_condition_type']               = $this->concatenateIntoString($conditions['condition'], 'condition_type');
            $data[$activityId]['Activity_conditions_condition_narrative_xml_lang'] = $this->concatenateIntoString($conditions['condition'], 'narrative', true, 'language');
            $data[$activityId]['Activity_conditions_condition_narrative_text']     = $this->concatenateIntoString($conditions['condition'], 'narrative', true, 'narrative');
        }

        return $data;
    }

    /**
     * Fill LegacyData data.
     * @param            $activityId
     * @param array      $data
     * @param array|null $legacyData
     * @return array
     */
    protected function fillLegacyData($activityId, array $data, array $legacyData = null)
    {
        if (!is_null($legacyData)) {
            $data[$activityId]['Activity_legacydata_name']            = $this->concatenateIntoString($legacyData, 'name');
            $data[$activityId]['Activity_legacydata_value']           = $this->concatenateIntoString($legacyData, 'value');
            $data[$activityId]['Activity_legacydata_iati_equivalent'] = $this->concatenateIntoString($legacyData, 'iati_equivalent');
        }

        return $data;
    }

    /**
     * Fill Related Activity data.
     * @param            $activityId
     * @param array      $data
     * @param array|null $relatedActivity
     * @return array
     */
    protected function fillRelatedActivityData($activityId, array $data, array $relatedActivity = null)
    {
        if (!is_null($relatedActivity)) {
            $data[$activityId]['Activity_relatedactivity_ref']  = $this->concatenateIntoString($relatedActivity, 'activity_identifier');
            $data[$activityId]['Activity_relatedactivity_type'] = $this->concatenateIntoString($relatedActivity, 'relationship_type');
        }

        return $data;
    }

    /**
     * Fill Humanitarian Scope data.
     * @param       $activityId
     * @param array $data
     * @param null  $humanitarianScope
     * @return array
     */
    protected function fillHumanitarianScopeData($activityId, array $data, $humanitarianScope = null)
    {
        if (!is_null($humanitarianScope)) {
            $data[$activityId]['Activity_humanitarianscope_type']               = $this->concatenateIntoString($humanitarianScope, 'type');
            $data[$activityId]['Activity_humanitarianscope_vocabulary']         = $this->concatenateIntoString($humanitarianScope, 'vocabulary');
            $data[$activityId]['Activity_humanitarianscope_vocabulary_uri']     = $this->concatenateIntoString($humanitarianScope, 'vocabulary_uri');
            $data[$activityId]['Activity_humanitarianscope_vocabulary_uri']     = $this->concatenateIntoString($humanitarianScope, 'vocabulary_uri');
            $data[$activityId]['Activity_humanitarianscope_narrative_xml_lang'] = $this->concatenateIntoString($humanitarianScope, 'narrative', true, 'language');
            $data[$activityId]['Activity_humanitarianscope_narrative_text']     = $this->concatenateIntoString($humanitarianScope, 'narrative', true, 'narrative');

        }

        return $data;
    }

    /**
     * Fill Document Link data.
     * @param       $activityId
     * @param array $data
     * @param null  $documentLinks
     * @return array
     */
    protected function fillDocumentLinkData($activityId, array $data, $documentLinks = null)
    {
        $documentLinkNarrativeData = $this->narrativeDataHolder;

        if (!is_null($documentLinks)) {
            $data[$activityId]['Activity_documentlink_format'] = $this->concatenateIntoString($documentLinks, 'format');
            $data[$activityId]['Activity_documentlink_url']    = $this->concatenateIntoString($documentLinks, 'url');

            foreach ($documentLinks as $documentLink) {
                $documentLinkNarrativeData['language'][]  = $this->concatenateIntoString($documentLink['title'][0]['narrative'], 'language');
                $documentLinkNarrativeData['narrative'][] = $this->concatenateIntoString($documentLink['title'][0]['narrative'], 'narrative');
            }
        }

        $data[$activityId]['Activity_documentlink_title_narrative_xml_lang'] = implode(';', $documentLinkNarrativeData['language']);
        $data[$activityId]['Activity_documentlink_title_narrative_text']     = implode(';', $documentLinkNarrativeData['narrative']);
        $data[$activityId]['Activity_documentlink_category_code']            = $this->concatenateIntoString($documentLinks, 'category', true, 'code');
        $data[$activityId]['Activity_documentlink_language_code']            = $this->concatenateIntoString($documentLinks, 'language', true, 'language');

        return $data;
    }

    /**
     * Fill Planned Disbursement data.
     * @param       $activityId
     * @param array $data
     * @param null  $plannedDisbursement
     * @return array
     */
    protected function fillPlannedDisbursementData($activityId, array $data, $plannedDisbursement = null)
    {
        if (!is_null($plannedDisbursement)) {
            $data[$activityId]['Activity_planneddisbursement_type']                 = $this->concatenateIntoString($plannedDisbursement, 'planned_disbursement_type');
            $data[$activityId]['Activity_planneddisbursement_periodstart_iso_date'] = $this->concatenateIntoString($plannedDisbursement, 'period_start', true, 'date');
            $data[$activityId]['Activity_planneddisbursement_periodend_iso_date']   = $this->concatenateIntoString($plannedDisbursement, 'period_end', true, 'date');
            $data[$activityId]['Activity_planneddisbursement_value_currency']       = $this->concatenateIntoString($plannedDisbursement, 'value', true, 'currency');
            $data[$activityId]['Activity_planneddisbursement_value_value_date']     = $this->concatenateIntoString($plannedDisbursement, 'value', true, 'value_date');
            $data[$activityId]['Activity_planneddisbursement_value_text']           = $this->concatenateIntoString($plannedDisbursement, 'value', true, 'amount');
        }

        return $data;
    }

    /**
     * Fill Budget data.
     * @param       $activityId
     * @param array $data
     * @param null  $budget
     * @return array
     */
    protected function fillBudgetData($activityId, array $data, $budget = null)
    {
        if (!is_null($budget)) {
            $data[$activityId]['Activity_budget_type']                 = $this->concatenateIntoString($budget, 'budget_type');
            $data[$activityId]['Activity_budget_periodstart_iso_date'] = $this->concatenateIntoString($budget[0]['period_start'], 'date');
            $data[$activityId]['Activity_budget_periodend_iso_date']   = $this->concatenateIntoString($budget[0]['period_end'], 'date');
            $data[$activityId]['Activity_budget_value_currency']       = $this->concatenateIntoString($budget, 'value', true, 'currency');
            $data[$activityId]['Activity_budget_value_value_date']     = $this->concatenateIntoString($budget, 'value', true, 'value_date');
            $data[$activityId]['Activity_budget_value_text']           = $this->concatenateIntoString($budget, 'value', true, 'amount');
        }

        return $data;
    }

    /**
     * Fill Policy Marker data.
     * @param       $activityId
     * @param array $data
     * @param null  $policyMarker
     * @return array
     */
    protected function fillPolicyMakerData($activityId, array $data, $policyMarker = null)
    {
        if (!is_null($policyMarker)) {
            $data[$activityId]['Activity_policymarker_vocabulary']         = $this->concatenateIntoString($policyMarker, 'vocabulary');
            $data[$activityId]['Activity_policymarker_code']               = $this->concatenateIntoString($policyMarker, 'policy_marker');
            $data[$activityId]['Activity_policymarker_significance']       = $this->concatenateIntoString($policyMarker, 'significance');
            $data[$activityId]['Activity_policymarker_narrative_xml_lang'] = $this->concatenateIntoString($policyMarker, 'narrative', true, 'language');
            $data[$activityId]['Activity_policymarker_narrative_text']     = $this->concatenateIntoString($policyMarker, 'narrative', true, 'narrative');
        }

        return $data;
    }

    /**
     * Fill Country Budget Items data.
     * @param       $activityId
     * @param array $data
     * @param       $countryBudgetItems
     * @return array
     */
    protected function fillCountryBudgetItemsData($activityId, array $data, $countryBudgetItems)
    {
        $dataHolder = $this->narrativeDataHolder;

        if (!is_null($countryBudgetItems)) {
            $data[$activityId]['Activity_countrybudgetitems_vocabulary']            = $countryBudgetItems[0]['vocabulary'];
            $data[$activityId]['Activity_countrybudgetitems_budgetitem_code']       = $this->concatenateIntoString($countryBudgetItems, 'budget_item', true, 'code');
            $data[$activityId]['Activity_countrybudgetitems_budgetitem_percentage'] = $this->concatenateIntoString($countryBudgetItems, 'budget_item', true, 'percentage');

            foreach ($countryBudgetItems[0]['budget_item'] as $budgetItem) {
                $dataHolder['language'][]  = $this->concatenateIntoString($budgetItem['description'], 'narrative', true, 'language');
                $dataHolder['narrative'][] = $this->concatenateIntoString($budgetItem['description'], 'narrative', true, 'narrative');
            }

            $data[$activityId]['Activity_countrybudgetitems_budgetitem_description_narrative_xml_lang'] = implode(';', $dataHolder['language']);
            $data[$activityId]['Activity_countrybudgetitems_budgetitem_description_narrative_text']     = implode(';', $dataHolder['narrative']);
        }

        return $data;
    }

    /**
     * Fill Sectors data.
     * @param       $activityId
     * @param array $data
     * @param null  $sectors
     * @return array
     */
    protected function fillSectorData($activityId, array $data, $sectors = null)
    {
        if (!is_null($sectors)) {
            $data[$activityId]['Activity_sector_vocabulary']         = $this->concatenateIntoString($sectors, 'sector_vocabulary');
            $data[$activityId]['Activity_sector_code']               = $this->concatenateIntoString($sectors, 'sector_code');
            $data[$activityId]['Activity_sector_percentage']         = $this->concatenateIntoString($sectors, 'percentage');
            $data[$activityId]['Activity_sector_narrative_xml_lang'] = $this->concatenateIntoString($sectors, 'narrative', true, 'language');
            $data[$activityId]['Activity_sector_narrative_text']     = $this->concatenateIntoString($sectors, 'narrative', true, 'narrative');
        }

        return $data;
    }

    /**
     * Fill Locations data.
     * @param       $activityId
     * @param array $data
     * @param null  $locations
     * @return array
     */
    protected function fillLocationData($activityId, array $data, $locations = null)
    {
        $locationDataHolder = [
            'reach'                => [],
            'id'                   => ['code' => [], 'vocabulary' => []],
            'name'                 => $this->narrativeDataHolder,
            'description'          => $this->narrativeDataHolder,
            'activity_description' => $this->narrativeDataHolder,
            'administrative'       => ['vocabulary' => [], 'level' => [], 'code' => []],
            'point'                => ['srsName' => [], 'latitude' => [], 'longitude' => []],
            'exactness'            => [],
            'class'                => [],
            'feature_designation'  => []
        ];

        if (!is_null($locations)) {
            $data[$activityId]['Activity_location_ref'] = $this->concatenateIntoString($locations, 'reference');

            foreach ($locations as $location) {
                $locationDataHolder['reach'][]                             = $this->concatenateIntoString($location['location_reach'], 'code');
                $locationDataHolder['id']['vocabulary'][]                  = $this->concatenateIntoString($location['location_id'], 'vocabulary');
                $locationDataHolder['id']['code'][]                        = $this->concatenateIntoString($location['location_id'], 'code');
                $locationDataHolder['name']['language'][]                  = $this->concatenateIntoString($location['name'][0]['narrative'], 'language');
                $locationDataHolder['name']['narrative'][]                 = $this->concatenateIntoString($location['name'][0]['narrative'], 'narrative');
                $locationDataHolder['description']['language'][]           = $this->concatenateIntoString($location['location_description'][0]['narrative'], 'language');
                $locationDataHolder['description']['narrative'][]          = $this->concatenateIntoString($location['location_description'][0]['narrative'], 'narrative');
                $locationDataHolder['activity_description']['language'][]  = $this->concatenateIntoString($location['activity_description'][0]['narrative'], 'language');
                $locationDataHolder['activity_description']['narrative'][] = $this->concatenateIntoString($location['activity_description'][0]['narrative'], 'narrative');
                $locationDataHolder['administrative']['vocabulary'][]      = $this->concatenateIntoString($location['administrative'], 'vocabulary');
                $locationDataHolder['administrative']['level'][]           = $this->concatenateIntoString($location['administrative'], 'level');
                $locationDataHolder['administrative']['code'][]            = $this->concatenateIntoString($location['administrative'], 'code');
                $locationDataHolder['point']['srsName'][]                  = $this->concatenateIntoString($location['point'], 'srs_name');
                $locationDataHolder['point']['latitude'][]                 = $this->concatenateIntoString($location['point'][0]['position'], 'latitude');
                $locationDataHolder['point']['longitude'][]                = $this->concatenateIntoString($location['point'][0]['position'], 'longitude');
                $locationDataHolder['exactness'][]                         = $this->concatenateIntoString($location['exactness'], 'code');
                $locationDataHolder['class'][]                             = $this->concatenateIntoString($location['location_class'], 'code');
                $locationDataHolder['feature_designation'][]               = $this->concatenateIntoString($location['feature_designation'], 'code');
            }

            $data[$activityId]['Activity_location_locationreach_code']                     = implode(';', $locationDataHolder['reach']);
            $data[$activityId]['Activity_location_locationid_vocabulary']                  = implode(';', $locationDataHolder['id']['vocabulary']);
            $data[$activityId]['Activity_location_locationid_code']                        = implode(';', $locationDataHolder['id']['code']);
            $data[$activityId]['Activity_location_name_narrative_xml_lang']                = implode(';', $locationDataHolder['name']['language']);
            $data[$activityId]['Activity_location_name_narrative_text']                    = implode(';', $locationDataHolder['name']['narrative']);
            $data[$activityId]['Activity_location_description_narrative_xml_lang']         = implode(';', $locationDataHolder['description']['language']);
            $data[$activityId]['Activity_location_description_narrative_text']             = implode(';', $locationDataHolder['description']['narrative']);
            $data[$activityId]['Activity_location_activitydescription_narrative_xml_lang'] = implode(';', $locationDataHolder['activity_description']['language']);
            $data[$activityId]['Activity_location_activitydescription_narrative_text']     = implode(';', $locationDataHolder['activity_description']['narrative']);
            $data[$activityId]['Activity_location_administrative_vocabulary']              = implode(';', $locationDataHolder['administrative']['vocabulary']);
            $data[$activityId]['Activity_location_administrative_level']                   = implode(';', $locationDataHolder['administrative']['level']);
            $data[$activityId]['Activity_location_administrative_code']                    = implode(';', $locationDataHolder['administrative']['code']);
            $data[$activityId]['Activity_location_point_srsname']                          = implode(';', $locationDataHolder['point']['srsName']);
            $data[$activityId]['Activity_location_point_pos_latitude']                     = implode(';', $locationDataHolder['point']['latitude']);
            $data[$activityId]['Activity_location_point_pos_longitude']                    = implode(';', $locationDataHolder['point']['longitude']);
            $data[$activityId]['Activity_location_exactness_code']                         = implode(';', $locationDataHolder['exactness']);
            $data[$activityId]['Activity_location_locationclass_code']                     = implode(';', $locationDataHolder['class']);
            $data[$activityId]['Activity_location_featuredesignation_code']                = implode(';', $locationDataHolder['feature_designation']);
        }

        return $data;
    }

    /**
     * Fill Recipient Region data.
     * @param       $activityId
     * @param array $data
     * @param null  $recipientRegion
     * @return array
     */
    protected function fillRecipientRegionData($activityId, array $data, $recipientRegion = null)
    {
        $regionInfo = $this->narrativeDataHolder;

        if (!is_null($recipientRegion)) {
            $data[$activityId]['Activity_recipientregion_code']       = $this->concatenateIntoString($recipientRegion, 'region_code');
            $data[$activityId]['Activity_recipientregion_vocabulary'] = $this->concatenateIntoString($recipientRegion, 'region_vocabulary');
            $data[$activityId]['Activity_recipientregion_percentage'] = $this->concatenateIntoString($recipientRegion, 'percentage');

            foreach ($recipientRegion as $region) {
                $regionInfo['language'][]  = $this->concatenateIntoString($region['narrative'], 'language');
                $regionInfo['narrative'][] = $this->concatenateIntoString($region['narrative'], 'narrative');
            }

            $data[$activityId]['Activity_recipientregion_narrative_xml_lang'] = implode(';', $regionInfo['language']);
            $data[$activityId]['Activity_recipientregion_narrative_text']     = implode(';', $regionInfo['narrative']);
        }

        return $data;
    }

    /**
     * Fill Recipient Country data.
     * @param       $activityId
     * @param array $data
     * @param null  $recipientCountry
     * @return array
     */
    protected function fillRecipientCountryData($activityId, array $data, $recipientCountry = null)
    {
        if (!is_null($recipientCountry)) {
            $data[$activityId]['Activity_recipientcountry_code']       = $this->concatenateIntoString($recipientCountry, 'country_code');
            $data[$activityId]['Activity_recipientcountry_percentage'] = $this->concatenateIntoString($recipientCountry, 'percentage');
            $countryInfo                                               = $this->narrativeDataHolder;

            foreach ($recipientCountry as $country) {
                $countryInfo['language'][]  = $this->concatenateIntoString($country['narrative'], 'language');
                $countryInfo['narrative'][] = $this->concatenateIntoString($country['narrative'], 'narrative');
            }

            $data[$activityId]['Activity_recipientcountry_narrative_xml_lang'] = implode(';', $countryInfo['language']);
            $data[$activityId]['Activity_recipientcountry_narrative_text']     = implode(';', $countryInfo['narrative']);

        }

        return $data;
    }

    /**
     * Fill Contact Info data.
     * @param       $activityId
     * @param array $data
     * @param null  $contactInfo
     * @return array
     */
    protected function fillContactInfoData($activityId, array $data, $contactInfo = null)
    {
        if (!is_null($contactInfo)) {
            $data[$activityId]['Activity_contactinfo_type']           = $this->concatenateIntoString($contactInfo, 'type');
            $data[$activityId]['Activity_contactinfo_telephone_text'] = $this->concatenateIntoString($contactInfo, 'telephone', true, 'telephone');
            $data[$activityId]['Activity_contactinfo_email_text']     = $this->concatenateIntoString($contactInfo, 'email', true, 'email');
            $data[$activityId]['Activity_contactinfo_website_text']   = $this->concatenateIntoString($contactInfo, 'website', true, 'website');

            $temporaryPlaceholder = [
                'organization'    => $this->narrativeDataHolder,
                'department'      => $this->narrativeDataHolder,
                'person_name'     => $this->narrativeDataHolder,
                'job_title'       => $this->narrativeDataHolder,
                'mailing_address' => $this->narrativeDataHolder
            ];


            foreach ($contactInfo as $contact) {
                $temporaryPlaceholder['organization']['language'][]     = $this->concatenateIntoString($contact['organization'][0]['narrative'], 'language');
                $temporaryPlaceholder['organization']['narrative'][]    = $this->concatenateIntoString($contact['organization'][0]['narrative'], 'narrative');
                $temporaryPlaceholder['department']['language'][]       = $this->concatenateIntoString($contact['department'][0]['narrative'], 'language');
                $temporaryPlaceholder['department']['narrative'][]      = $this->concatenateIntoString($contact['department'][0]['narrative'], 'narrative');
                $temporaryPlaceholder['person_name']['language'][]      = $this->concatenateIntoString($contact['person_name'][0]['narrative'], 'language');
                $temporaryPlaceholder['person_name']['narrative'][]     = $this->concatenateIntoString($contact['person_name'][0]['narrative'], 'narrative');
                $temporaryPlaceholder['job_title']['language'][]        = $this->concatenateIntoString($contact['job_title'][0]['narrative'], 'language');
                $temporaryPlaceholder['job_title']['narrative'][]       = $this->concatenateIntoString($contact['job_title'][0]['narrative'], 'narrative');
                $temporaryPlaceholder['mailing_address']['language'][]  = $this->concatenateIntoString($contact['mailing_address'][0]['narrative'], 'language');
                $temporaryPlaceholder['mailing_address']['narrative'][] = $this->concatenateIntoString($contact['mailing_address'][0]['narrative'], 'narrative');
            }

            $data[$activityId]['Activity_contactinfo_organisation_narrative_xml_lang']   = implode(';', $temporaryPlaceholder['organization']['language']);
            $data[$activityId]['Activity_contactinfo_organisation_narrative_text']       = implode(';', $temporaryPlaceholder['organization']['narrative']);
            $data[$activityId]['Activity_contactinfo_department_narrative_xml_lang']     = implode(';', $temporaryPlaceholder['department']['language']);
            $data[$activityId]['Activity_contactinfo_department_narrative_text']         = implode(';', $temporaryPlaceholder['department']['narrative']);
            $data[$activityId]['Activity_contactinfo_personname_narrative_xml_lang']     = implode(';', $temporaryPlaceholder['person_name']['language']);
            $data[$activityId]['Activity_contactinfo_personname_narrative_text']         = implode(';', $temporaryPlaceholder['person_name']['narrative']);
            $data[$activityId]['Activity_contactinfo_jobtitle_narrative_xml_lang']       = implode(';', $temporaryPlaceholder['job_title']['language']);
            $data[$activityId]['Activity_contactinfo_jobtitle_narrative_text']           = implode(';', $temporaryPlaceholder['job_title']['narrative']);
            $data[$activityId]['Activity_contactinfo_mailingaddress_narrative_xml_lang'] = implode(';', $temporaryPlaceholder['mailing_address']['language']);
            $data[$activityId]['Activity_contactinfo_mailingaddress_narrative_text']     = implode(';', $temporaryPlaceholder['mailing_address']['narrative']);
        }

        return $data;
    }

    /**
     * Fill Activity Date data.
     * @param       $activityId
     * @param array $data
     * @param       $activityDate
     * @return array
     */
    protected function fillActivityDateData($activityId, array $data, $activityDate)
    {
        if (!is_null($activityDate)) {
            $data[$activityId]['Activity_activitydate_iso_date']           = $this->concatenateIntoString($activityDate, 'date');
            $data[$activityId]['Activity_activitydate_type']               = $this->concatenateIntoString($activityDate, 'type');
            $data[$activityId]['Activity_activitydate_narrative_xml_lang'] = $this->concatenateIntoString($activityDate, 'narrative', true, 'language');
            $data[$activityId]['Activity_activitydate_narrative_text']     = $this->concatenateIntoString($activityDate, 'narrative', true, 'narrative');
        }

        return $data;
    }

    /**
     * Fill Other Activity Identifier data.
     * @param       $activityId
     * @param array $data
     * @param null  $otherIdentifier
     * @return array
     */
    protected function fillOtherActivityIdentifierData($activityId, array $data, $otherIdentifier = null)
    {
        $holder = $this->narrativeDataHolder;

        if (!is_null($otherIdentifier)) {
            $data[$activityId]['Activity_otheractivityidentifier_ref']          = $this->concatenateIntoString($otherIdentifier, 'reference');
            $data[$activityId]['Activity_otheractivityidentifier_type']         = $this->concatenateIntoString($otherIdentifier, 'type');
            $data[$activityId]['Activity_otheractivityidentifier_ownerorg_ref'] = $this->concatenateIntoString($otherIdentifier, 'owner_org', true, 'reference');


            foreach ($otherIdentifier as $identifier) {
                if (is_array($identifier) && boolval($identifier['owner_org'])) {
                    foreach ($identifier['owner_org'][0]['narrative'] as $narrative) {
                        $holder['language'][]  = $narrative['language'];
                        $holder['narrative'][] = $narrative['narrative'];
                    }
                }
            }

            $data[$activityId]['Activity_otheractivityidentifier_ownerorg_narrative_xml_lang'] = implode(';', $holder['language']);
            $data[$activityId]['Activity_otheractivityidentifier_ownerorg_narrative_text']     = implode(';', $holder['narrative']);
        }

        return $data;
    }

    /**
     * Fill Participating Organization data.
     * @param       $activityId
     * @param array $data
     * @param null  $participatingOrganization
     * @return array
     */
    protected function fillParticipatingOrgData($activityId, array $data, $participatingOrganization = null)
    {
        if (!is_null($participatingOrganization)) {
            $data[$activityId]['Activity_participatingorg_ref']                = $this->concatenateIntoString($participatingOrganization, 'identifier');
            $data[$activityId]['Activity_participatingorg_role']               = $this->concatenateIntoString($participatingOrganization, 'organization_role');
            $data[$activityId]['Activity_participatingorg_type']               = $this->concatenateIntoString($participatingOrganization, 'organization_type');
            $data[$activityId]['Activity_participatingorg_narrative_xml_lang'] = $this->concatenateIntoString($participatingOrganization, 'narrative', true, 'language');
            $data[$activityId]['Activity_participatingorg_narrative_text']     = $this->concatenateIntoString($participatingOrganization, 'narrative', true, 'narrative');
        }

        return $data;
    }

    /**
     * Fill Activity Description data.
     * @param       $activityId
     * @param array $data
     * @param null  $activityDescription
     * @return array
     */
    protected function fillDescriptionData($activityId, array $data, $activityDescription = null)
    {
        if (!is_null($activityDescription)) {
            $data[$activityId]['Activity_description_type']               = $this->concatenateIntoString($activityDescription, 'type');
            $data[$activityId]['Activity_description_narrative_xml_lang'] = $this->concatenateIntoString($activityDescription, 'narrative', true, 'language');
            $data[$activityId]['Activity_description_narrative_text']     = $this->concatenateIntoString($activityDescription, 'narrative', true, 'narrative');
        }

        return $data;
    }

    /**
     * Fill Reporting Organization data.
     * @param       $activityId
     * @param array $data
     * @param null  $reportingOrganisation
     * @return array
     */
    protected function fillReportingOrgData($activityId, array $data, $reportingOrganisation = null)
    {
        if (!is_null($reportingOrganisation)) {
            $data[$activityId]['Activity_reportingorg_ref']                = $reportingOrganisation[0]['reporting_organization_identifier'];
            $data[$activityId]['Activity_reportingorg_type']               = $reportingOrganisation[0]['reporting_organization_type'];
            $data[$activityId]['Activity_reportingorg_narrative_xml_lang'] = $this->concatenateIntoString($reportingOrganisation[0]['narrative'], 'language');
            $data[$activityId]['Activity_reportingorg_narrative_text']     = $this->concatenateIntoString($reportingOrganisation[0]['narrative'], 'narrative');
        }

        return $data;
    }

    /**
     * Fill Activity Title data.
     * @param       $activityId
     * @param array $data
     * @param null  $activityTitle
     * @return array
     */
    protected function fillTitleData($activityId, array $data, $activityTitle = null)
    {
        if (!is_null($activityTitle)) {
            $data[$activityId]['Activity_title_narrative_xml_lang'] = $this->concatenateIntoString($activityTitle, 'language');
            $data[$activityId]['Activity_title_narrative_text']     = $this->concatenateIntoString($activityTitle, 'narrative');
        }

        return $data;
    }
}
