<?php namespace App\Core\V201\Formatter\Factory\Traits;


use Illuminate\Database\Eloquent\Collection;

/**
 * Class CompleteCsvFactory
 * @package App\Core\V201\Formatter\Factory
 */
trait CompleteCsvFactory
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
     * Factory method for reportingOrg Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function reportingOrg(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId            = $activity->id;
            $reportingOrganisation = $activity->organization->reporting_org;

            if (!is_null($reportingOrganisation)) {
                $data[$activityId]['Activity_reportingorg_ref']                = $reportingOrganisation[0]['reporting_organization_identifier'];
                $data[$activityId]['Activity_reportingorg_type']               = $reportingOrganisation[0]['reporting_organization_type'];
                $data[$activityId]['Activity_reportingorg_narrative_xml_lang'] = $this->concatenateIntoString($reportingOrganisation[0]['narrative'], 'language');
                $data[$activityId]['Activity_reportingorg_narrative_text']     = $this->concatenateIntoString($reportingOrganisation[0]['narrative'], 'narrative');
            }
        }

        return $data;
    }

    /**
     * Factory method for Title Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function title(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;

            if (!is_null($activity['title'])) {
                $data[$activityId]['Activity_title_narrative_xml_lang'] = $this->concatenateIntoString($activity['title'], 'language');
                $data[$activityId]['Activity_title_narrative_text']     = $this->concatenateIntoString($activity['title'], 'narrative');
            }
        }

        return $data;
    }

    /**
     * Factory method for Description Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function description(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId          = $activity->id;
            $activityDescription = $activity['description'];

            if (!is_null($activityDescription)) {
                $data[$activityId]['Activity_description_type']               = $this->concatenateIntoString($activityDescription, 'type');
                $data[$activityId]['Activity_description_narrative_xml_lang'] = $this->concatenateIntoString($activityDescription, 'narrative', true, 'language');
                $data[$activityId]['Activity_description_narrative_text']     = $this->concatenateIntoString($activityDescription, 'narrative', true, 'narrative');
            }
        }

        return $data;
    }

    /**
     * Factory method for Participating Organization Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function participatingOrg(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId                = $activity->id;
            $participatingOrganization = $activity->participating_organization;

            if (!is_null($participatingOrganization)) {
                $data[$activityId]['Activity_participatingorg_ref']                = $this->concatenateIntoString($participatingOrganization, 'identifier');
                $data[$activityId]['Activity_participatingorg_role']               = $this->concatenateIntoString($participatingOrganization, 'organization_role');
                $data[$activityId]['Activity_participatingorg_type']               = $this->concatenateIntoString($participatingOrganization, 'organization_type');
                $data[$activityId]['Activity_participatingorg_narrative_xml_lang'] = $this->concatenateIntoString($participatingOrganization, 'narrative', true, 'language');
                $data[$activityId]['Activity_participatingorg_narrative_text']     = $this->concatenateIntoString($participatingOrganization, 'narrative', true, 'narrative');
            }
        }

        return $data;
    }

    /**
     * Factory method for Other Activity Identifier Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function otherActivityIdentifier(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId      = $activity->id;
            $otherIdentifier = $activity->other_identifier;
            $holder          = $this->narrativeDataHolder;

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
        }

        return $data;
    }

    /**
     * Factory method for Activity Status Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function activityStatus(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_activitystatus_code'] = $activity->activity_status;
        }

        return $data;
    }

    /**
     * Factory method for Activity Date Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function activityDate(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId   = $activity->id;
            $activityDate = $activity->activity_date;

            if (!is_null($activityDate)) {
                $data[$activityId]['Activity_activitydate_iso_date']           = $this->concatenateIntoString($activityDate, 'date');
                $data[$activityId]['Activity_activitydate_type']               = $this->concatenateIntoString($activityDate, 'type');
                $data[$activityId]['Activity_activitydate_narrative_xml_lang'] = $this->concatenateIntoString($activityDate, 'narrative', true, 'language');
                $data[$activityId]['Activity_activitydate_narrative_text']     = $this->concatenateIntoString($activityDate, 'narrative', true, 'narrative');
            }
        }

        return $data;
    }

    /**
     * Factory method for ContactInfo Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function contactInfo(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId  = $activity->id;
            $contactInfo = $activity->contact_info;

            if (!is_null($contactInfo)) {
                $data[$activityId]['Activity_contactinfo_type']           = $this->concatenateIntoString($contactInfo, 'type');
                $data[$activityId]['Activity_contactinfo_telephone_text'] = $this->concatenateIntoString($contactInfo, 'telephone', true, 'telephone');
                $data[$activityId]['Activity_contactinfo_email_text']     = $this->concatenateIntoString($contactInfo, 'email', true, 'email');
                $data[$activityId]['Activity_contactinfo_website_text']   = $this->concatenateIntoString($contactInfo, 'website', true, 'website');

                $holder = $this->narrativeDataHolder;

                $temporaryPlaceholder = [
                    'organization'    => $holder,
                    'department'      => $holder,
                    'person_name'     => $holder,
                    'job_title'       => $holder,
                    'mailing_address' => $holder
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
        }

        return $data;
    }

    /**
     * Factory method for Activity Scope Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function activityScope(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_activityscope_code'] = $activity->activity_scope;
        }

        return $data;
    }

    /**
     * Factory method for RecipientCountry Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function recipientCountry(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId       = $activity->id;
            $recipientCountry = $activity->recipient_country;

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
        }

        return $data;
    }

    /**
     * Factory method for RecipientRegion Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function recipientRegion(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId      = $activity->id;
            $recipientRegion = $activity->recipient_region;
            $regionInfo      = $this->narrativeDataHolder;

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
        }

        return $data;
    }

    /**
     * Factory method for Location Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function location(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId         = $activity->id;
            $locations          = $activity->location;
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
        }

        return $data;
    }

    /**
     * Factory method for Sector Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function sector(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $sectors    = $activity->sector;

            if (!is_null($sectors)) {
                $data[$activityId]['Activity_sector_vocabulary']         = $this->concatenateIntoString($sectors, 'sector_vocabulary');
                $data[$activityId]['Activity_sector_code']               = $this->concatenateIntoString($sectors, 'sector_code');
                $data[$activityId]['Activity_sector_percentage']         = $this->concatenateIntoString($sectors, 'percentage');
                $data[$activityId]['Activity_sector_narrative_xml_lang'] = $this->concatenateIntoString($sectors, 'narrative', true, 'language');
                $data[$activityId]['Activity_sector_narrative_text']     = $this->concatenateIntoString($sectors, 'narrative', true, 'narrative');
            }

        }

        return $data;
    }

    /**
     * Factory method for CountryBudgetItem Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function countryBudgetItems(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId         = $activity->id;
            $countryBudgetItems = $activity->country_budget_items;
            $dataHolder         = $this->narrativeDataHolder;

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
        }

        return $data;
    }

    /**
     * Factory method for PolicyMarker Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function policyMarker(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId   = $activity->id;
            $policyMarker = $activity->policy_maker;

            if (!is_null($policyMarker)) {
                $data[$activityId]['Activity_policymarker_vocabulary']         = $this->concatenateIntoString($policyMarker, 'vocabulary');
                $data[$activityId]['Activity_policymarker_code']               = $this->concatenateIntoString($policyMarker, 'policy_marker');
                $data[$activityId]['Activity_policymarker_significance']       = $this->concatenateIntoString($policyMarker, 'significance');
                $data[$activityId]['Activity_policymarker_narrative_xml_lang'] = $this->concatenateIntoString($policyMarker, 'narrative', true, 'language');
                $data[$activityId]['Activity_policymarker_narrative_text']     = $this->concatenateIntoString($policyMarker, 'narrative', true, 'narrative');
            }

        }

        return $data;
    }

    /**
     * Factory method for CollaborationType Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function collaborationType(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_collaborationtype_code'] = $activity->collaboration_type;
        }

        return $data;
    }

    /**
     * Factory method for DefaultFlowType Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function defaultFlowType(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_defaultflowtype_code'] = $activity->default_flow_type;
        }

        return $data;
    }

    /**
     * Factory method for DefaultFinanceType Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function defaultFinanceType(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_defaultfinancetype_code'] = $activity->default_finance_type;
        }

        return $data;
    }

    /**
     * Factory method for DefaultAidType Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function defaultAidType(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_defaultaidtype_code'] = $activity->default_aid_type;
        }

        return $data;
    }

    /**
     * Factory method for DefaultTiedStatus Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function defaultTiedStatus(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_defaulttiedstatus_code'] = $activity->default_tied_status;
        }

        return $data;
    }

    /**
     * Factory method for Budget Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function budget(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $budget     = $activity->budget;

            if (!is_null($budget)) {
                $data[$activityId]['Activity_budget_type']                 = $this->concatenateIntoString($budget, 'budget_type');
                $data[$activityId]['Activity_budget_periodstart_iso_date'] = $this->concatenateIntoString($budget[0]['period_start'], 'date');
                $data[$activityId]['Activity_budget_periodend_iso_date']   = $this->concatenateIntoString($budget[0]['period_end'], 'date');
                $data[$activityId]['Activity_budget_value_currency']       = $this->concatenateIntoString($budget, 'value', true, 'currency');
                $data[$activityId]['Activity_budget_value_value_date']     = $this->concatenateIntoString($budget, 'value', true, 'value_date');
                $data[$activityId]['Activity_budget_value_text']           = $this->concatenateIntoString($budget, 'value', true, 'amount');
            }

        }

        return $data;
    }

    /**
     * Factory method for PlannedDisbursement Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function plannedDisbursement(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId          = $activity->id;
            $plannedDisbursement = $activity->planned_disbursement;

            if (!is_null($plannedDisbursement)) {
                $data[$activityId]['Activity_planneddisbursement_type']                 = $this->concatenateIntoString($plannedDisbursement, 'planned_disbursement_type');
                $data[$activityId]['Activity_planneddisbursement_periodstart_iso_date'] = $this->concatenateIntoString($plannedDisbursement, 'period_start', true, 'date');
                $data[$activityId]['Activity_planneddisbursement_periodend_iso_date']   = $this->concatenateIntoString($plannedDisbursement, 'period_end', true, 'date');
                $data[$activityId]['Activity_planneddisbursement_value_currency']       = $this->concatenateIntoString($plannedDisbursement, 'value', true, 'currency');
                $data[$activityId]['Activity_planneddisbursement_value_value_date']     = $this->concatenateIntoString($plannedDisbursement, 'value', true, 'value_date');
                $data[$activityId]['Activity_planneddisbursement_value_text']           = $this->concatenateIntoString($plannedDisbursement, 'value', true, 'amount');
            }
        }

        return $data;
    }

    /**
     * Factory method for CapitalSpend Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function capitalSpend(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $data[$activity->id]['Activity_capitalspend_percentage'] = $activity->capital_spend;
        }

        return $data;
    }

    /**
     * Factory method for DocumentLink Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function documentLink(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId                = $activity->id;
            $documentLinks             = $activity->document_link;
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
        }

        return $data;
    }

    /**
     * Factory method for RelatedActivity Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function relatedActivity(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId      = $activity->id;
            $relatedActivity = $activity->related_activity;
            $data            = $this->fillRelatedActivityData($activityId, $data, $relatedActivity);
        }

        return $data;
    }

    /**
     * Factory method for RelatedActivity Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function legacyData(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $legacyData = $activity->legacy_data;
            $data       = $this->fillLegacyData($activityId, $data, $legacyData);
        }

        return $data;
    }

    /**
     * Factory method for Conditions Element.
     * @param array      $data
     * @param Collection $activities
     * @return array
     */
    protected function conditions(array $data, Collection $activities)
    {
        foreach ($activities as $activity) {
            $activityId = $activity->id;
            $conditions = $activity->conditions;
            $data       = $this->fillConditionData($activityId, $data, $conditions);
        }

        return $data;
    }
// Factories end here...

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
}
