<?php namespace App\Services\Xml\Validator;


/**
 * Class XmlValidator
 * @package App\Services\Xml\Validator
 */
class XmlValidator
{
    /**
     * @var
     */
    protected $activity;

    /**
     * @var Validation
     */
    protected $factory;

    /**
     * @param Validation $factory
     */
    public function __construct(Validation $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param $activity
     * @return $this
     */
    public function init($activity)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @param      $activityId
     * @param bool $shouldBeUnique
     * @return array
     */
    public function validateActivity($activityId, $shouldBeUnique = false)
    {
        return $this->factory->initialize($this->activity, $this->rules(), $this->messages())
                             ->passes()
                             ->withErrors($activityId, $shouldBeUnique);
    }

    /**
     * Returns the required validation rules for an Activity.
     *
     * @return array
     */
    public function rules()
    {
        $activity                      = $this->activity;
        $rules                         = [];
        $rules                         = array_merge($rules, $this->rulesForTitle($activity));
        $rules                         = array_merge($rules, $this->rulesForDescription($activity));
        $rules                         = array_merge($rules, $this->rulesForOtherIdentifier($activity));
        $rules['activity_status']      = sprintf('required|in:%s', $this->validCodeList('ActivityStatus', 'V201'));
        $rules                         = array_merge($rules, $this->rulesForActivityDate($activity));
        $rules                         = array_merge($rules, $this->rulesForContactInfo($activity));
        $rules['activity_scope']       = sprintf('in:%s', $this->validCodeList('ActivityScope', 'V201'));
        $rules                         = array_merge($rules, $this->rulesForParticipatingOrg($activity));
        $rules                         = array_merge($rules, $this->rulesForRecipientCountry($activity));
        $rules                         = array_merge($rules, $this->rulesForRecipientRegion($activity));
        $rules                         = array_merge($rules, $this->rulesForLocation($activity));
        $rules                         = array_merge($rules, $this->rulesForSector($activity));
        $rules                         = array_merge($rules, $this->rulesForCountryBudgetItems($activity));
        $rules                         = array_merge($rules, $this->rulesForHumanitarianScope($activity));
        $rules                         = array_merge($rules, $this->rulesForPolicyMarker($activity));
        $rules['collaboration_type']   = sprintf('in:%s', $this->validCodeList('CollaborationType', 'V201'));
        $rules['default_flow_type']    = sprintf('in:%s', $this->validCodeList('FlowType', 'V201'));
        $rules['default_finance_type'] = sprintf('in:%s', $this->validCodeList('FinanceType', 'V201'));
        $rules['default_aid_type']     = sprintf('in:%s', $this->validCodeList('AidType', 'V201'));
        $rules['default_tied_status']  = sprintf('in:%s', $this->validCodeList('TiedStatus', 'V201'));
        $rules                         = array_merge($rules, $this->rulesForBudget($activity));
        $rules                         = array_merge($rules, $this->rulesForPlannedDisbursement($activity));
        $rules['capital_spend']        = 'numeric|max:100|min:0';
        $rules                         = array_merge($rules, $this->rulesForDocumentLink($activity));
        $rules                         = array_merge($rules, $this->rulesForRelatedActivity($activity));
        $rules                         = array_merge($rules, $this->rulesForLegacyData($activity));
        $rules                         = array_merge($rules, $this->rulesForCondition($activity));
        $rules                         = array_merge($rules, $this->rulesForTransaction($activity));
        $rules                         = array_merge($rules, $this->rulesForResult($activity));

        return $rules;
    }


    /**
     * Returns the required messages for the failed validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $activity                             = $this->activity;
        $messages                             = [];
        $messages                             = array_merge($messages, $this->messagesForTitle($activity));
        $messages                             = array_merge($messages, $this->messagesForDescription($activity));
        $messages                             = array_merge($messages, $this->messagesForOtherIdentifier($activity));
        $messages['activity_status.required'] = 'Activity Status is required';
        $messages['activity_status.in']       = 'Activity Status code is incorrect';
        $messages                             = array_merge($messages, $this->messagesForActivityDate($activity));
        $messages                             = array_merge($messages, $this->messagesForContactInfo($activity));
        $messages['activity_scope.required']  = 'Activity Scope is required';
        $messages['activity_scope.in']        = 'Activity Scope code is incorrect';
        $messages                             = array_merge($messages, $this->messagesForParticipatingOrg($activity));
        $messages                             = array_merge($messages, $this->messagesForRecipientCountry($activity));
        $messages                             = array_merge($messages, $this->messagesForRecipientRegion($activity));
        $messages                             = array_merge($messages, $this->messagesForLocation($activity));
        $messages                             = array_merge($messages, $this->messagesForSector($activity));
        $messages                             = array_merge($messages, $this->messagesForCountryBudgetItems($activity));
        $messages                             = array_merge($messages, $this->messagesForHumanitarianScope($activity));
        $messages                             = array_merge($messages, $this->messagesForPolicyMarker($activity));
        $messages['collaboration_type.in']    = 'Entered Collaboration type is incorrect';
        $messages['default_flow_type.in']     = 'Entered Flow type is incorrect';
        $messages['default_finance_type.in']  = 'Entered Finance type is incorrect';
        $messages['default_aid_type.in']      = 'Entered Aid type is incorrect';
        $messages['default_tied_status.in']   = 'Entered Tied Status code is incorrect';
        $messages                             = array_merge($messages, $this->messagesForBudget($activity));
        $messages                             = array_merge($messages, $this->rulesForPlannedDisbursement($activity));
        $messages['capital_spend.numeric']    = 'Please enter data in number';
        $messages['capital_spend.max']        = 'Capital spend cannot be more than 100';
        $messages['capital_spend.min']        = 'Capital spend cannot be negative';
        $messages                             = array_merge($messages, $this->messagesForDocumentLink($activity));
        $messages                             = array_merge($messages, $this->messagesForRelatedActivity($activity));
        $messages                             = array_merge($messages, $this->messagesForLegacyData($activity));
        $messages                             = array_merge($messages, $this->messagesForCondition($activity));
        $messages                             = array_merge($messages, $this->messagesForTransaction($activity));
        $messages                             = array_merge($messages, $this->messagesForResult($activity));

        return $messages;
    }

    /**
     * @param array $activity
     * @return mixed
     */
    protected function rulesForTitle(array $activity)
    {
        $title                      = getVal($activity, ['title'], []);
        $rules['title']             = 'required';
        $rules['title.*.narrative'] = 'unique_lang|unique_default_lang';
        foreach ($title as $narrativeIndex => $narrative) {
            $rules[sprintf('title.%s.narrative', $narrativeIndex)] = sprintf('required_with:%s', 'title.' . $narrativeIndex . '.language');
        }

        return $rules;
    }

    /**
     * Messages for Title.
     *
     * @param array $activity
     * @return mixed
     */
    protected function messagesForTitle(array $activity)
    {
        $title                         = getVal($activity, ['title'], []);
        $messages['title.required']    = 'Title is required';
        $messages['title.unique_lang'] = 'Language should be unique.';
        foreach ($title as $narrativeIndex => $narrative) {
            $messages[sprintf('title.%s.narrative.required_with', $narrativeIndex)] = 'Title is required with narrative';
        }

        return $messages;
    }

    /**
     * Rules for Description.
     *
     * @param array $activity
     * @return array
     */
    protected function rulesForDescription(array $activity)
    {
        $rules        = [];
        $descriptions = getVal($activity, ['description']);

        foreach ($descriptions as $descriptionIndex => $description) {
            $rules[sprintf('description.%s.type', $descriptionIndex)] = sprintf('required|in:%s', $this->validCodeList('DescriptionType', 'V201'));
            $rules                                                    = array_merge(
                $rules,
                $this->factory->getRulesForNarrative(getVal($description, ['narrative'], []), sprintf('description.%s', $descriptionIndex))
            );
        }

        return $rules;
    }

    /**
     * Messages for Description.
     *
     * @param array $activity
     * @return array
     */
    protected function messagesForDescription(array $activity)
    {
        $messages     = [];
        $descriptions = getVal($activity, ['description']);

        foreach ($descriptions as $descriptionIndex => $description) {
            $messages[sprintf('description.%s.type.required', $descriptionIndex)] = 'Description Type is required';
            $messages[sprintf('description.%s.type.in', $descriptionIndex)]       = 'Invalid description type';
            $messages                                                             = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative(getVal($description, ['narrative'], []), sprintf('description.%s', $descriptionIndex))
            );
        }

        return $messages;
    }

    /**
     * Rules for Other Identifier.
     *
     * @param array $activity
     * @return array
     */
    public function rulesForOtherIdentifier(array $activity)
    {
        $rules            = [];
        $otherIdentifiers = getVal($activity, ['other_identifier'], []);

        foreach ($otherIdentifiers as $otherIdentifierIndex => $otherIdentifier) {
            $otherIdentifierBase                                  = sprintf('other_identifier.%s', $otherIdentifierIndex);
            $rules[sprintf('%s.reference', $otherIdentifierBase)] = 'required';
            $rules[sprintf('%s.type', $otherIdentifierBase)]      = sprintf('required|in:%s', $this->validCodeList('OtherIdentifierType', 'V201'));
            $rules                                                = array_merge(
                $rules,
                $this->rulesForOwnerOrg(getVal($otherIdentifier, ['owner_org'], []), $otherIdentifierBase)
            );
        }

        return $rules;
    }

    /**
     * Messages for Other Identifier.
     *
     * @param array $activity
     * @return array
     */
    public function messagesForOtherIdentifier(array $activity)
    {
        $messages         = [];
        $otherIdentifiers = getVal($activity, ['other_identifier'], []);

        foreach ($otherIdentifiers as $otherIdentifierIndex => $otherIdentifier) {
            $otherIdentifierBase                                              = sprintf('other_identifier.%s', $otherIdentifierIndex);
            $messages[sprintf('%s.reference.required', $otherIdentifierBase)] = 'Reference is required';
            $messages[sprintf('%s.type.required', $otherIdentifierBase)]      = 'Type is required';
            $messages[sprintf('%s.type.in', $otherIdentifierBase)]            = 'Other Identifier type is incorrect';
            $messages                                                         = array_merge(
                $messages,
                $this->messagesForOwnerOrg(getVal($otherIdentifier, ['owner_org'], []), $otherIdentifierBase)
            );
        }

        return $messages;
    }

    /**
     * Rules for Owner Organization.
     *
     * @param $ownerOrgData
     * @param $otherIdentifierBase
     * @return array
     */
    public function rulesForOwnerOrg($ownerOrgData, $otherIdentifierBase)
    {
        $rules = [];

        foreach ($ownerOrgData as $ownerOrgIndex => $ownerOrg) {
            $ownerOrgBase = sprintf('%s.owner_org.%s', $otherIdentifierBase, $ownerOrgIndex);
            $rules        = array_merge(
                $rules,
                $this->factory->getRulesForNarrative(getVal($ownerOrg, ['narrative'], []), $ownerOrgBase)
            );
        }

        return $rules;
    }

    /**
     * Messages for Owner Organization.
     *
     * @param $ownerOrgData
     * @param $otherIdentifierBase
     * @return array
     */
    public function messagesForOwnerOrg($ownerOrgData, $otherIdentifierBase)
    {
        $messages = [];

        foreach ($ownerOrgData as $ownerOrgIndex => $ownerOrg) {
            $ownerOrgBase = sprintf('%s.owner_org.%s', $otherIdentifierBase, $ownerOrgIndex);
            $messages     = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($ownerOrg['narrative'], $ownerOrgBase)
            );
        }

        return $messages;
    }

    /**
     * Rules for Activity Date.
     *
     * @param array $activity
     * @return array
     */
    protected function rulesForActivityDate(array $activity)
    {
        $rules         = [];
        $activityDates = getVal($activity, ['activity_date'], []);

        $rules['activity_date'] = 'required|start_date_required|start_end_date';

        foreach ($activityDates as $activityDateIndex => $activityDate) {
            $activityDateBase                             = sprintf('activity_date.%s', $activityDateIndex);
            $rules[sprintf('%s.type', $activityDateBase)] = sprintf('required|in:%s', $this->validCodeList('ActivityDateType', 'V201'));
            $rules[sprintf('%s.date', $activityDateBase)] = 'date|actual_date|required';
            $rules                                        = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($activityDate['narrative'], $activityDateBase)
            );
        }

        return $rules;
    }

    /**
     * Messages for Activity Date.
     *
     * @param array $activity
     * @return array
     */
    protected function messagesForActivityDate(array $activity)
    {
        $messages      = [];
        $activityDates = getVal($activity, ['activity_date'], []);

        $messages = [
            'activity_date.required'            => 'Activity Date is required.',
            'activity_date.start_date_required' => 'Actual Start Date or Planned Start Date is required.',
            'activity_date.start_end_date'      => 'Actual Start Date or Planned Start Date should be before Actual End Date or Planned End Date.',
        ];

        foreach ($activityDates as $activityDateIndex => $activityDate) {
            $activityDateBase                                            = sprintf('activity_date.%s', $activityDateIndex);
            $messages[sprintf('%s.date.required', $activityDateBase)]    = 'Date is required';
            $messages[sprintf('%s.date.actual_date', $activityDateBase)] = 'Actual Start Date And Actual End Date must not exceed present date';
            $messages[sprintf('%s.date.date', $activityDateBase)]        = 'Please enter valid date in Activity Date.';
            $messages[sprintf('%s.type.required', $activityDateBase)]    = 'Type is required';
            $messages[sprintf('%s.type.in', $activityDateBase)]          = 'Invalid Activity Date type';
            $messages                                                    = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($activityDate['narrative'], $activityDateBase)
            );
        }

        return $messages;
    }

    /**
     * Rules for Contact Info.
     *
     * @param array $activity
     * @return array
     */
    protected function rulesForContactInfo(array $activity)
    {
        $rules    = [];
        $contacts = getVal($activity, ['contact_info'], []);

        foreach ($contacts as $contactInfoIndex => $contactInfo) {
            $contactInfoBase                             = sprintf('contact_info.%s', $contactInfoIndex);
            $rules[sprintf('%s.type', $contactInfoBase)] = sprintf('in:%s', $this->validCodeList('ContactType', 'V201'));
            $rules                                       = array_merge(
                $rules,
                $this->getRulesForDepartment(getVal($contactInfo, ['department'], []), $contactInfoBase),
                $this->getRulesForOrganization(getVal($contactInfo, ['organization'], []), $contactInfoBase),
                $this->getRulesForPersonName(getVal($contactInfo, ['person_name'], []), $contactInfoBase),
                $this->getRulesForJobTitle(getVal($contactInfo, ['job_title'], []), $contactInfoBase),
                $this->getRulesForMailingAddress(getVal($contactInfo, ['mailing_address'], []), $contactInfoBase),
                $this->getRulesForEmail(getVal($contactInfo, ['email'], []), $contactInfoBase),
                $this->getRulesForWebsite(getVal($contactInfo, ['website'], []), $contactInfoBase)
            );
        }

        return $rules;
    }

    /**
     * Messages for Contact Info.
     *
     * @param array $activity
     * @return array
     */
    protected function messagesForContactInfo(array $activity)
    {
        $messages = [];
        $contacts = getVal($activity, ['contact_info'], []);

        foreach ($contacts as $contactInfoIndex => $contactInfo) {
            $contactInfoBase                                   = sprintf('contact_info.%s', $contactInfoIndex);
            $messages[sprintf('%s.type.in', $contactInfoBase)] = 'Invalid Contact Info Type';
            $messages                                          = array_merge(
                $messages,
                $this->getMessagesForDepartment(getVal($contactInfo, ['department'], []), $contactInfoBase),
                $this->getMessagesForOrganization(getVal($contactInfo, ['organization'], []), $contactInfoBase),
                $this->getMessagesForPersonName(getVal($contactInfo, ['person_name'], []), $contactInfoBase),
                $this->getMessagesForJobTitle(getVal($contactInfo, ['job_title'], []), $contactInfoBase),
                $this->getMessagesForMailingAddress(getVal($contactInfo, ['mailing_address'], []), $contactInfoBase),
                $this->getMessagesForEmail(getVal($contactInfo, ['email'], []), $contactInfoBase),
                $this->getMessagesForWebsite(getVal($contactInfo, ['website'], []), $contactInfoBase)
            );
        }

        return $messages;
    }

    /**
     * Rules for Contact Info Organization.
     *
     * @param $organizationData
     * @param $contactBase
     * @return array
     */
    protected function getRulesForOrganization($organizationData, $contactBase)
    {
        $rules = [];

        foreach ($organizationData as $organizationIndex => $organization) {
            $organizationBase = sprintf('%s.organization.%s', $contactBase, $organizationIndex);
            $rules            = array_merge($rules, $this->factory->getRulesForNarrative($organization['narrative'], $organizationBase));
        }

        return $rules;
    }

    /**
     * Messages for Contact Info Organization.
     *
     * @param $organizationData
     * @param $contactBase
     * @return array
     */
    protected function getMessagesForOrganization($organizationData, $contactBase)
    {
        $messages = [];

        foreach ($organizationData as $organizationIndex => $organization) {
            $organizationBase = sprintf('%s.organization.%s', $contactBase, $organizationIndex);
            $messages         = array_merge($messages, $this->factory->getMessagesForNarrative($organization['narrative'], $organizationBase));
        }

        return $messages;
    }

    /**
     * Rules for Contact Info Department.
     *
     * @param $departments
     * @param $contactBase
     * @return array
     */
    protected function getRulesForDepartment($departments, $contactBase)
    {
        $rules = [];

        foreach ($departments as $departmentIndex => $department) {
            $departmentBase = sprintf('%s.department.%s', $contactBase, $departmentIndex);
            $rules          = array_merge($rules, $this->factory->getRulesForNarrative($department['narrative'], $departmentBase));
        }

        return $rules;
    }

    /**
     * Messages for Contact Info Department.
     *
     * @param $departments
     * @param $contactBase
     * @return array
     */
    protected function getMessagesForDepartment($departments, $contactBase)
    {
        $messages = [];

        foreach ($departments as $departmentIndex => $department) {
            $departmentBase = sprintf('%s.department.%s', $contactBase, $departmentIndex);
            $messages       = array_merge($messages, $this->factory->getMessagesForNarrative($department['narrative'], $departmentBase));
        }

        return $messages;
    }

    /**
     * Rules for Contact Info Person Name.
     *
     * @param $personNames
     * @param $contactBase
     * @return array
     */
    protected function getRulesForPersonName($personNames, $contactBase)
    {
        $rules = [];

        foreach ($personNames as $personNameIndex => $personName) {
            $personNameBase = sprintf('%s.person_name.%s', $contactBase, $personNameIndex);
            $rules          = array_merge($rules, $this->factory->getRulesForNarrative($personName['narrative'], $personNameBase));
        }

        return $rules;
    }

    /**
     * Messages for Contact Info Person Name.
     *
     * @param $personNames
     * @param $contactBase
     * @return array
     */
    protected function getMessagesForPersonName($personNames, $contactBase)
    {
        $messages = [];

        foreach ($personNames as $personNameIndex => $personName) {
            $personNameBase = sprintf('%s.person_name.%s', $contactBase, $personNameIndex);
            $messages       = array_merge($messages, $this->factory->getMessagesForNarrative($personName['narrative'], $personNameBase));
        }

        return $messages;
    }

    /**
     * Rules for Contact Info Job Title.
     *
     * @param $jobTitles
     * @param $contactBase
     * @return array
     */
    protected function getRulesForJobTitle($jobTitles, $contactBase)
    {
        $rules = [];

        foreach ($jobTitles as $jobTitleIndex => $jobTitle) {
            $jobTitleBase = sprintf('%s.job_title.%s', $contactBase, $jobTitleIndex);
            $rules        = array_merge($rules, $this->factory->getRulesForNarrative($jobTitle['narrative'], $jobTitleBase));
        }

        return $rules;
    }

    /**
     * Messages for Contact Info Job Title.
     *
     * @param $jobTitles
     * @param $contactBase
     * @return array
     */
    protected function getMessagesForJobTitle($jobTitles, $contactBase)
    {
        $messages = [];

        foreach ($jobTitles as $jobTitleIndex => $jobTitle) {
            $jobTitleBase = sprintf('%s.job_title.%s', $contactBase, $jobTitleIndex);
            $messages     = array_merge($messages, $this->factory->getMessagesForNarrative($jobTitle['narrative'], $jobTitleBase));
        }

        return $messages;
    }

    /**
     * Rules for Contact Info Mailing Address.
     *
     * @param $mailingAddresses
     * @param $contactBase
     * @return array
     */
    protected function getRulesForMailingAddress($mailingAddresses, $contactBase)
    {
        $rules = [];

        foreach ($mailingAddresses as $mailingAddressIndex => $mailingAddress) {
            $mailingAddressBase = sprintf('%s.mailing_address.%s', $contactBase, $mailingAddressIndex);
            $rules              = array_merge($rules, $this->factory->getRulesForNarrative($mailingAddress['narrative'], $mailingAddressBase));
        }

        return $rules;
    }

    /**
     * Messages for Contact Info Mailing Address.
     *
     * @param $mailingAddresses
     * @param $contactBase
     * @return array
     */
    protected function getMessagesForMailingAddress($mailingAddresses, $contactBase)
    {
        $messages = [];

        foreach ($mailingAddresses as $mailingAddressIndex => $mailingAddress) {
            $mailingAddressBase = sprintf('%s.mailing_address.%s', $contactBase, $mailingAddressIndex);
            $messages           = array_merge($messages, $this->factory->getMessagesForNarrative($mailingAddress['narrative'], $mailingAddressBase));
        }

        return $messages;
    }


    /**
     * Rules for Contact Info Email.
     *
     * @param $emails
     * @param $contactBase
     * @return array
     */
    protected function getRulesForEmail($emails, $contactBase)
    {
        $rules = [];

        foreach ($emails as $emailIndex => $email) {
            $rules[sprintf('%s.email.%s.email', $contactBase, $emailIndex)] = 'email';
        }

        return $rules;
    }

    /**
     * Messages for Contact Info Email.
     *
     * @param $emails
     * @param $contactBase
     * @return array
     */
    protected function getMessagesForEmail($emails, $contactBase)
    {
        $messages = [];

        foreach ($emails as $emailIndex => $email) {
            $messages[sprintf('%s.email.%s.email.email', $contactBase, $emailIndex)] = 'Email must be a valid email address.';
        }

        return $messages;
    }

    /**
     * Rules for Contact Info Website.
     *
     * @param $websites
     * @param $contactBase
     * @return array
     */
    protected function getRulesForWebsite($websites, $contactBase)
    {
        $rules = [];

        foreach ($websites as $websiteIndex => $website) {
            $rules[sprintf('%s.website.%s.website', $contactBase, $websiteIndex)] = 'url';
        }

        return $rules;
    }

    /**
     * Messages for Contact Info Website.
     *
     * @param $websites
     * @param $contactBase
     * @return array
     */
    protected function getMessagesForWebsite($websites, $contactBase)
    {
        $messages = [];

        foreach ($websites as $websiteIndex => $website) {
            $messages[sprintf('%s.website.%s.website.url', $contactBase, $websiteIndex)] = 'Enter valid URL. eg. http://example.com';
        }

        return $messages;
    }

    /**
     * returns rules for participating organization
     * @param array $activity
     * @return array|mixed
     */
    public function rulesForParticipatingOrg(array $activity)
    {
        $rules                      = [];
        $participatingOrganizations = getVal($activity, ['participating_organization'], []);

        foreach ($participatingOrganizations as $participatingOrgIndex => $participatingOrg) {
            $participatingOrgBase                                = 'participating_organization.' . $participatingOrgIndex;
            $rules[$participatingOrgBase . '.organization_role'] = sprintf('required|in:%s', $this->validCodeList('OrganisationRole', 'V201'));
            $rules[$participatingOrgBase . '.organization_type'] = sprintf('in:%s', $this->validCodeList('OrganisationType', 'V201'));
            $identifier                                          = $participatingOrgBase . '.identifier';
            $narrative                                           = sprintf('%s.narrative.0.narrative', $participatingOrgBase);
            $rules[$identifier]                                  = 'exclude_operators|required_without:' . $narrative;
            $rules[$narrative][]                                 = 'required_without:' . $identifier;
            $rules                                               = array_merge_recursive(
                $rules,
                $this->factory->getRulesForNarrative($participatingOrg['narrative'], $participatingOrgBase)
            );
        }

        return $rules;
    }

    /**
     * returns messages for participating organization
     * @param array $activity
     * @return array|mixed
     */
    public function messagesForParticipatingOrg(array $activity)
    {
        $messages                   = [];
        $participatingOrganizations = getVal($activity, ['participating_organization'], []);

        foreach ($participatingOrganizations as $participatingOrgIndex => $participatingOrg) {
            $participatingOrgBase                                            = 'participating_organization.' . $participatingOrgIndex;
            $messages[$participatingOrgBase . '.organization_role.required'] = 'Organization role is required';
            $messages[$participatingOrgBase . '.organization_role.in']       = 'Organization role is incorrect in Participating Organisation';
            $messages[$participatingOrgBase . '.organization_type.in']       = 'Organization type is incorrect in Participating Organisation';
            $identifier                                                      = $participatingOrgBase . '.identifier';
            $narrative                                                       = sprintf('%s.narrative.0.narrative', $participatingOrgBase);
            $messages[$identifier . '.required_without']                     = 'Identifier is required when Narrative is not present.';
            $messages[$narrative . '.required_without']                      = 'Narrative is required when Identifier is not present.';
            $messages                                                        = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($participatingOrg['narrative'], $participatingOrgBase)
            );
        }

        return $messages;
    }

    /**
     * returns rules for recipient country form
     * @param array $activity
     * @return array
     */
    public function rulesForRecipientCountry(array $activity)
    {
        $rules              = [];
        $recipientCountries = getVal($activity, ['recipient_country'], []);
        $recipientRegion    = getVal($activity, ['recipient_region']);

        foreach ($recipientCountries as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryBase                           = 'recipient_country.' . $recipientCountryIndex;
            $rules[$recipientCountryBase . '.country_code'] = sprintf('required|in:%s', $this->validCodeList('Country', 'V201', 'Organization'));
            $rules[$recipientCountryBase . '.percentage']   = 'numeric|max:100';
            if (count($recipientCountries) > 1 || $recipientRegion != null) {
                $rules[$recipientCountryBase . '.percentage'] = 'required|numeric|max:100';
            }
            $rules = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($recipientCountry['narrative'], $recipientCountryBase)
            );
        }

        return $rules;
    }

    /**
     * returns messages for recipient country form rules
     * @param array $activity
     * @return array
     */
    public function messagesForRecipientCountry(array $activity)
    {
        $messages           = [];
        $recipientCountries = getVal($activity, ['recipient_country'], []);

        foreach ($recipientCountries as $recipientCountryIndex => $recipientCountry) {
            $recipientCountryBase                                       = 'recipient_country.' . $recipientCountryIndex;
            $messages[$recipientCountryBase . '.country_code.required'] = 'Country code field is required';
            $messages[$recipientCountryBase . '.country_code.in']       = 'Country code field is incorrect in Recipient Country';
            $messages[$recipientCountryBase . '.percentage.numeric']    = 'Percentage must be a number';
            $messages[$recipientCountryBase . '.percentage.max']        = 'Percentage may not be greater than 100';
            $messages[$recipientCountryBase . '.percentage.required']   = 'Percentage is required';
            $messages                                                   = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($recipientCountry['narrative'], $recipientCountryBase)
            );
        }

        return $messages;
    }

    /**
     * returns rules for recipient region
     * @param array $activity
     * @return array|mixed
     */
    public function rulesForRecipientRegion(array $activity)
    {
        $rules            = [];
        $recipientRegions = getVal($activity, ['recipient_region'], []);
        $recipientCountry = getVal($activity, ['recipient_country'], []);

        foreach ($recipientRegions as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionBase                          = 'recipient_region.' . $recipientRegionIndex;
            $rules[$recipientRegionBase . '.region_code'] = sprintf('required|in:%s', $this->validCodeList('Region', 'V201'));
            $rules[$recipientRegionBase . '.percentage']  = 'numeric|max:100';
            if (count($recipientRegions) > 1 || $recipientCountry != null) {
                $rules[$recipientRegionBase . '.percentage'] = 'required|numeric|max:100';
            }
            $rules = array_merge(
                $rules,
                $this->factory->getRulesForNarrative(getVal($recipientRegion, ['narrative'], []), $recipientRegionBase)
            );
        }

        return $rules;
    }

    /**
     * returns messages for recipient region m
     * @param array $activity
     * @return array|mixed
     */
    public function messagesForRecipientRegion(array $activity)
    {
        $messages         = [];
        $recipientRegions = getVal($activity, ['recipient_region'], []);

        foreach ($recipientRegions as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionBase                                      = 'recipient_region.' . $recipientRegionIndex;
            $messages[$recipientRegionBase . '.region_code.required'] = 'Recipient region code is required';
            $messages[$recipientRegionBase . '.region_code.in']       = 'Region code is incorrect in Recipient Region';
            $messages[$recipientRegionBase . '.percentage.numeric']   = 'Percentage should be numeric.';
            $messages[$recipientRegionBase . '.percentage.max']       = 'Percentage should be less than or equal to 100';
            $messages[$recipientRegionBase . '.percentage.required']  = 'Percentage is required.';
            $messages                                                 = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative(getVal($recipientRegion, ['narrative'], []), $recipientRegionBase)
            );
        }

        return $messages;
    }

    /**
     * returns rules for location form
     * @param array $activity
     * @return array
     */
    protected function rulesForLocation(array $activity)
    {
        $rules     = [];
        $locations = getVal($activity, ['location'], []);

        foreach ($locations as $locationIndex => $location) {
            $locationBase                                                    = 'location.' . $locationIndex;
            $rules[sprintf('%s.location_reach.*.code', $locationBase)]       = sprintf('in:%s', $this->validCodeList('GeographicLocationReach', 'V201'));
            $rules[sprintf('%s.location_id.*.vocabulary', $locationBase)]    = sprintf('in:%s', $this->validCodeList('GeographicVocabulary', 'V201'));
            $rules[sprintf('%s.administrative.*.vocabulary', $locationBase)] = sprintf('in:%s', $this->validCodeList('GeographicVocabulary', 'V201'));
            $rules[sprintf('%s.exactness.*.code', $locationBase)]            = sprintf('in:%s', $this->validCodeList('GeographicExactness', 'V201'));
            $rules[sprintf('%s.location_class.*.code', $locationBase)]       = sprintf('in:%s', $this->validCodeList('GeographicLocationClass', 'V201'));
            $rules[sprintf('%s.feature_designation.*.code', $locationBase)]  = sprintf('in:%s', $this->validCodeList('LocationType', 'V201'));
            $rules                                                           = array_merge(
                $rules,
                $this->getRulesForLocationId(getVal($location, ['location_id'], []), $locationBase),
                $this->getRulesForName(getVal($location, ['name'], []), $locationBase),
                $this->getRulesForLocationDescription(getVal($location, ['location_description'], []), $locationBase),
                $this->getRulesForActivityDescription(getVal($location, ['activity_description'], []), $locationBase),
                $this->getRulesForAdministrative(getVal($location, ['administrative'], []), $locationBase),
                $this->getRulesForPoint(getVal($location, ['point'], []), $locationBase)
            );
        }

        return $rules;
    }

    /**
     * returns messages for location form
     * @param array $activity
     * @return array
     */
    protected function messagesForLocation(array $activity)
    {
        $messages  = [];
        $locations = getVal($activity, ['location'], []);

        foreach ($locations as $locationIndex => $location) {
            $locationBase                                                          = 'location.' . $locationIndex;
            $messages[sprintf('%s.location_reach.*.code.in', $locationBase)]       = 'Location reach code in incorrect in Location';
            $messages[sprintf('%s.location_id.*.vocabulary.in', $locationBase)]    = 'Location id vocabulary is incorrect in Location';
            $messages[sprintf('%s.administrative.*.vocabulary.in', $locationBase)] = 'Administrative vocabulary is incorrect in Location';
            $messages[sprintf('%s.exactness.*.code.in', $locationBase)]            = 'Exactness code is incorrect in Location';
            $messages[sprintf('%s.location_class.*.code.in', $locationBase)]       = 'Location class is incorrect in Location';
            $messages[sprintf('%s.feature_designation.*.code.in', $locationBase)]  = 'Feature Designation code is incorrect in Location';
            $messages                                                              = array_merge(
                $messages,
                $this->getMessagesForLocationId(getVal($location, ['location_id'], []), $locationBase),
                $this->getMessagesForName(getVal($location, ['name'], []), $locationBase),
                $this->getMessagesForLocationDescription(getVal($location, ['location_description'], []), $locationBase),
                $this->getMessagesForActivityDescription(getVal($location, ['activity_description'], []), $locationBase),
                $this->getMessagesForAdministrative(getVal($location, ['administrative'], []), $locationBase),
                $this->getMessagesForPoint(getVal($location, ['point'], []), $locationBase)
            );
        }

        return $messages;
    }

    /**
     * returns rules for location id
     * @param $locationsIds
     * @param $locationBase
     * @return array
     */
    protected function getRulesForLocationId($locationsIds, $locationBase)
    {
        $rules = [];
        foreach ($locationsIds as $locationIdIndex => $locationId) {
            $locationIdBase = sprintf('%s.location_id.%s', $locationBase, $locationIdIndex);
            if ($locationId['code'] != "") {
                $rules[sprintf('%s.vocabulary', $locationIdBase)] = 'required_with:' . sprintf('%s.code', $locationIdBase);
            }
            if ($locationId['vocabulary'] != "") {
                $rules[sprintf('%s.code', $locationIdBase)] = 'required_with:' . sprintf('%s.vocabulary', $locationIdBase);
            }
        }

        return $rules;
    }

    /**
     * returns messages for location id
     * @param $locationsIds
     * @param $locationBase
     * @return array
     */
    protected function getMessagesForLocationId($locationsIds, $locationBase)
    {
        $messages = [];
        foreach ($locationsIds as $locationIdIndex => $locationId) {
            $locationIdBase = sprintf('%s.location_id.%s', $locationBase, $locationIdIndex);
            if ($locationId['code'] != "") {
                $messages[sprintf('%s.vocabulary.required_with', $locationIdBase)] = 'Vocabulary is required when Code is present';
            }
            if ($locationId['vocabulary'] != "") {
                $messages[sprintf('%s.code.required_with', $locationIdBase)] = 'Code is required when Vocabulary is present';
            }
        }

        return $messages;
    }

    /**
     * returns rules for name
     * @param $locationName
     * @param $locationBase
     * @return array
     */
    protected function getRulesForName($locationName, $locationBase)
    {
        $rules = [];
        foreach ($locationName as $nameIndex => $name) {
            $narrativeBase = sprintf('%s.name.%s', $locationBase, $nameIndex);
            $rules         = array_merge($rules, $this->factory->getRulesForNarrative($name['narrative'], $narrativeBase));
        }

        return $rules;
    }

    /**
     * returns messages for name
     * @param $locationName
     * @param $locationBase
     * @return array
     */
    protected function getMessagesForName($locationName, $locationBase)
    {
        $messages = [];
        foreach ($locationName as $nameIndex => $name) {
            $narrativeBase = sprintf('%s.name.%s', $locationBase, $nameIndex);
            $messages      = array_merge($messages, $this->factory->getMessagesForNarrative($name['narrative'], $narrativeBase));
        }

        return $messages;
    }

    /**
     * returns rules for location description
     * @param $locationDescription
     * @param $locationBase
     * @return array
     */
    protected function getRulesForLocationDescription($locationDescription, $locationBase)
    {
        $rules = [];
        foreach ($locationDescription as $descriptionIndex => $description) {
            $narrativeBase = sprintf('%s.location_description.%s', $locationBase, $descriptionIndex);
            $rules         = array_merge($rules, $this->factory->getRulesForNarrative($description['narrative'], $narrativeBase));
        }

        return $rules;
    }

    /**
     * returns messages for location description
     * @param $locationDescription
     * @param $locationBase
     * @return array
     */
    protected function getMessagesForLocationDescription($locationDescription, $locationBase)
    {
        $messages = [];
        foreach ($locationDescription as $descriptionIndex => $description) {
            $narrativeBase = sprintf('%s.location_description.%s', $locationBase, $descriptionIndex);
            $messages      = array_merge($messages, $this->factory->getMessagesForNarrative($description['narrative'], $narrativeBase));
        }

        return $messages;
    }

    /**
     * returns rules for activity description
     * @param array $activityDescription
     * @param       $locationBase
     * @return array
     */
    protected function getRulesForActivityDescription($activityDescription, $locationBase)
    {
        $rules = [];
        foreach ($activityDescription as $descriptionIndex => $description) {
            $narrativeBase = sprintf('%s.activity_description.%s', $locationBase, $descriptionIndex);
            $rules         = array_merge($rules, $this->factory->getRulesForNarrative($description['narrative'], $narrativeBase));
        }

        return $rules;
    }

    /**
     * returns messages for activity description
     * @param array $activityDescription
     * @param       $locationBase
     * @return array
     */
    protected function getMessagesForActivityDescription($activityDescription, $locationBase)
    {
        $messages = [];
        foreach ($activityDescription as $descriptionIndex => $description) {
            $narrativeBase = sprintf('%s.activity_description.%s', $locationBase, $descriptionIndex);
            $messages      = array_merge($messages, $this->factory->getMessagesForNarrative($description['narrative'], $narrativeBase));
        }

        return $messages;
    }

    /**
     * returns rules for administrative
     * @param $administrativeData
     * @param $locationBase
     * @return array
     */
    protected function getRulesForAdministrative($administrativeData, $locationBase)
    {
        $rules = [];
        foreach ($administrativeData as $administrativeIndex => $administrative) {
            $administrativeBase                              = sprintf('%s.administrative.%s', $locationBase, $administrativeIndex);
            $rules[sprintf('%s.level', $administrativeBase)] = 'min:0|integer';
        }

        return $rules;
    }

    /**
     * returns messages for administrative
     * @param $administrativeData
     * @param $locationBase
     * @return array
     */
    protected function getMessagesForAdministrative($administrativeData, $locationBase)
    {
        $messages = [];
        foreach ($administrativeData as $administrativeIndex => $administrative) {
            $administrativeBase                                         = sprintf('%s.administrative.%s', $locationBase, $administrativeIndex);
            $messages[sprintf('%s.level.integer', $administrativeBase)] = 'Level should be a non-negative integer.';
        }

        return $messages;
    }

    /**
     * returns rules for point
     * @param $points
     * @param $locationBase
     * @return array
     */
    protected function getRulesForPoint($points, $locationBase)
    {
        $rules                                     = [];
        $pointBase                                 = sprintf('%s.point.0', $locationBase);
        $rules[sprintf('%s.srs_name', $pointBase)] = 'required';
        $positionBase                              = sprintf('%s.position.0', $pointBase);
        $latitude                                  = sprintf('%s.latitude', $positionBase);
        $longitude                                 = sprintf('%s.longitude', $positionBase);
        $rules[$latitude]                          = sprintf('required_with:%s|numeric', $longitude);
        $rules[$longitude]                         = sprintf('required_with:%s|numeric', $latitude);

        return $rules;
    }

    /**
     * returns messages for point
     * @param $formFields
     * @param $locationBase
     * @return array
     */
    protected function getMessagesForPoint($formFields, $locationBase)
    {
        $messages                                                       = [];
        $pointBase                                                      = sprintf('%s.point.0', $locationBase);
        $messages[sprintf('%s.srs_name.required', $pointBase)]          = 'SRS name is required.';
        $positionBase                                                   = sprintf('%s.position.0', $pointBase);
        $messages[sprintf('%s.latitude.required_with', $positionBase)]  = 'Latitude is required when Longitude is present.';
        $messages[sprintf('%s.latitude.numeric', $positionBase)]        = 'Latitude should be numeric.';
        $messages[sprintf('%s.longitude.required_with', $positionBase)] = 'Longitude is required when Latitude is present.';
        $messages[sprintf('%s.longitude.numeric', $positionBase)]       = 'Longitude should be numeric.';

        return $messages;
    }

    /**
     * returns rules for sector
     * @param array $activity
     * @return array|mixed
     */
    public function rulesForSector(array $activity)
    {
        $rules   = [];
        $sectors = getVal($activity, ['sector'], []);

        foreach ($sectors as $sectorIndex => $sector) {
            $sectorBase                                             = sprintf('sector.%s', $sectorIndex);
            $rules[sprintf('%s.vocabulary_uri', $sectorBase)]       = 'url';
            $rules[sprintf('%s.sector_vocabulary', $sectorBase)]    = sprintf('required|in:%s', $this->validCodeList('SectorVocabulary', 'V202'));
            $rules[sprintf('%s.sector_code', $sectorBase)]          = sprintf('in:%s', $this->validCodeList('Sector', 'V201'));
            $rules[sprintf('%s.sector_category_code', $sectorBase)] = sprintf('in:%s', $this->validCodeList('SectorCategory', 'V201'));

            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == 2) {
                if ($sector['sector_vocabulary'] == 1) {
                    $rules[sprintf('%s.sector_code', $sectorBase)] = sprintf('in:%s|required_with:' . $sectorBase . '.sector_vocabulary', $this->validCodeList('Sector', 'V201'));
                }
                if ($sector['sector_code'] != "") {
                    $rules[sprintf('%s.sector_vocabulary', $sectorBase)] = sprintf('in:%s|required_with:' . $sectorBase . '.sector_code', $this->validCodeList('SectorVocabulary', 'V202'));
                }
                if ($sector['sector_vocabulary'] == 2) {
                    $rules[sprintf('%s.sector_category_code', $sectorBase)] = sprintf('in:%s|required_with:' . $sectorBase . '.sector_vocabulary', $this->validCodeList('SectorCategory', 'V201'));
                }
                if ($sector['sector_category_code'] != "") {
                    $rules[sprintf('%s.sector_vocabulary', $sectorBase)] = sprintf('in:%s|required_with:' . $sectorBase . '.sector_category_code', $this->validCodeList('SectorVocabulary', 'V202'));
                }
            } else {
                if ($sector['sector_vocabulary'] != "") {
                    $rules[sprintf('%s.sector_text', $sectorBase)] = 'required_with:' . $sectorBase . '.sector_vocabulary';
                }

                if ($sector['sector_text'] != "") {
                    $rules[sprintf('%s.sector_vocabulary', $sectorBase)] = sprintf('in:%s|required_with:' . $sectorBase . '.sector_text', $this->validCodeList('SectorVocabulary', 'V202'));
                }

                if ($sector['sector_vocabulary'] == "99" || $sector['sector_vocabulary'] == "98") {
                    $rules[sprintf('%s.vocabulary_uri', $sectorBase)] = 'url|required_with:' . $sectorBase . '.sector_vocabulary';
                }
            }

            $rules[sprintf('%s.percentage', $sectorBase)] = 'numeric|max:100';
            if (count($sectors) > 1) {
                $rules[sprintf('%s.percentage', $sectorBase)] = 'required|numeric|max:100';
            }
            $rules = array_merge($rules, $this->factory->getRulesForNarrative($sector['narrative'], $sectorBase));
        }

        $totalPercentage = $this->getRulesForPercentage($sectors);

        $indexes = [];

        foreach ($totalPercentage as $index => $value) {
            if (is_numeric($index) && $value != 100) {
                $indexes[] = $index;
            }
        }

        $fields = [];

        foreach ($totalPercentage as $i => $percentage) {
            foreach ($indexes as $index) {
                if ($index == $percentage) {
                    $fields[] = $i;
                }
            }
        }

        foreach ($fields as $field) {
            $rules[$field] = 'required|sum|numeric|max:100';
        }

        return $rules;
    }

    /**
     * returns messages for sector
     * @param array $activity
     * @return array|mixed
     */
    public function messagesForSector(array $activity)
    {
        $messages = [];
        $sectors  = getVal($activity, ['sector'], []);

        foreach ($sectors as $sectorIndex => $sector) {
            $sectorBase                                                      = sprintf('sector.%s', $sectorIndex);
            $messages[sprintf('%s.vocabulary_uri.url', $sectorBase)]         = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('%s.sector_vocabulary.required', $sectorBase)] = 'Sector vocabulary is required';
            $messages[sprintf('%s.sector_vocabulary.in', $sectorBase)]       = 'Sector vocabulary is incorrect';
            $messages[sprintf('%s.sector_code.in', $sectorBase)]             = 'Sector code is incorrect for vocabulary 1';
            $messages[sprintf('%s.sector_category_code.in', $sectorBase)]    = 'Sector code is incorrect for vocabulary 2';

            if ($sector['sector_vocabulary'] == 1 || $sector['sector_vocabulary'] == 2) {
                if ($sector['sector_vocabulary'] == 1) {
                    $messages[sprintf('%s.sector_code.%s', $sectorBase, 'required_with')] = 'Sector code is required with Sector vocabulary.';
                }
                if ($sector['sector_code'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorBase, 'required_with')] = 'Sector vocabulary is required with Sector code.';
                }
                if ($sector['sector_vocabulary'] == 2) {
                    $messages[sprintf('%s.sector_category_code.%s', $sectorBase, 'required_with')] = 'Sector code is required with Sector vocabulary.';
                }
                if ($sector['sector_category_code'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorBase, 'required_with')] = 'Sector vocabulary is required with Sector code.';
                }
            } else {
                if ($sector['sector_vocabulary'] != "") {
                    $messages[sprintf('%s.sector_text.%s', $sectorBase, 'required_with')] = 'Sector code is required with Sector vocabulary.';
                }

                if ($sector['sector_text'] != "") {
                    $messages[sprintf('%s.sector_vocabulary.%s', $sectorBase, 'required_with')] = 'Sector vocabulary is required with Sector code.';
                }

                if ($sector['sector_vocabulary'] == "99" || $sector['sector_vocabulary'] == "98") {
                    $messages[sprintf('%s.vocabulary_uri.%s', $sectorBase, 'required_with')] = 'Vocabulary URI is required with Sector vocabulary.';
                }
            }

            $messages[sprintf('%s.percentage.numeric', $sectorBase)]  = 'Percentage should be numeric.';
            $messages[sprintf('%s.percentage.max', $sectorBase)]      = 'Percentage should be less than or equal to :max.';
            $messages[sprintf('%s.percentage.required', $sectorBase)] = 'Percentage is required.';
            $messages[sprintf('%s.percentage.sum', $sectorBase)]      = 'Total percentage of sectors under the same vocabulary must be equal to 100.';
            $messages                                                 = array_merge($messages, $this->factory->getMessagesForNarrative($sector['narrative'], $sectorBase));
        }

        return $messages;
    }

    /**
     * write brief description
     * @param $sectors
     * @return array
     */
    protected function getRulesForPercentage($sectors)
    {
        $array           = [];
        $totalPercentage = 0;

        if (count($sectors) > 1) {
            foreach ($sectors as $sectorIndex => $sector) {
                $sectorForm       = sprintf('sector.%s', $sectorIndex);
                $percentage       = $sector['percentage'];
                $sectorVocabulary = $sector['sector_vocabulary'];

                if (array_key_exists($sectorVocabulary, $array)) {
                    $totalPercentage                              = $array[$sectorVocabulary] + $percentage;
                    $array[$sectorVocabulary]                     = $totalPercentage;
                    $array[sprintf('%s.percentage', $sectorForm)] = $sectorVocabulary;

                } else {
                    $array[$sectorVocabulary] = $percentage;

                    $array[sprintf('%s.percentage', $sectorForm)] = $sectorVocabulary;
                }
            }
        }

        return $array;
    }


    /**
     * returns rules for country budget item form
     * @param array $activity
     * @return array
     */
    public function rulesForCountryBudgetItems(array $activity)
    {
        $rules              = [];
        $countryBudgetItems = getVal($activity, ['country_budget_items'], []);

        foreach ($countryBudgetItems as $countryBudgetItemIndex => $countryBudgetItem) {
            $countryBudgetItemBase                                                = sprintf('country_budget_items.%s', $countryBudgetItemIndex);
            $code                                                                 = getVal($countryBudgetItem, ['vocabulary'], '') == 1 ? 'code' : 'code_text';
            $rules[sprintf('%s.budget_item.0.%s', $countryBudgetItemBase, $code)] = 'required';
            $rules[sprintf('%s.vocabulary', $countryBudgetItemBase)]              = sprintf('required|in:%s', $this->validCodeList('BudgetIdentifierVocabulary', 'V201'));
            $rules                                                                = array_merge(
                $rules,
                $this->getBudgetItemRules($countryBudgetItem['budget_item'], $countryBudgetItemBase, $code, $countryBudgetItems)
            );
        }

        return $rules;
    }

    /**
     * returns messages for country budget error messages
     * @param array $activity
     * @return array
     */
    public function messagesForCountryBudgetItems(array $activity)
    {
        $messages           = [];
        $countryBudgetItems = getVal($activity, ['country_budget_items'], []);

        foreach ($countryBudgetItems as $countryBudgetItemIndex => $countryBudgetItem) {
            $countryBudgetItemBase                                                            = sprintf('country_budget_items.%s', $countryBudgetItemIndex);
            $code                                                                             = getVal($countryBudgetItem, ['vocabulary'], '') == 1 ? 'code' : 'code_text';
            $messages[sprintf('%s.budget_item.0.%s.required', $countryBudgetItemBase, $code)] = 'Code is Required in Country Budget Items';
            $messages[sprintf('%s.vocabulary.required', $countryBudgetItemBase)]              = 'Vocabulary is required in Country Budget Items';
            $messages[sprintf('%s.vocabulary.in', $countryBudgetItemBase)]                    = 'Vocabulary is incorrect in Country Budget Items';
            $messages                                                                         = array_merge(
                $messages,
                $this->getBudgetItemMessages(getVal($countryBudgetItem, ['budget_item'], []), $countryBudgetItemBase, $code)
            );
        }

        return $messages;
    }

    /**
     * returns budget item validation rules
     * @param $budgetItems
     * @param $countryBudgetItemBase
     * @param $code
     * @param $countryBudgetItems
     * @return array
     */
    public function getBudgetItemRules($budgetItems, $countryBudgetItemBase, $code, $countryBudgetItems)
    {
        $rules = [];
        foreach ($budgetItems as $budgetItemIndex => $budgetItem) {
            $budgetItemBase                                   = sprintf('%s.budget_item.%s', $countryBudgetItemBase, $budgetItemIndex);
            $rules[sprintf('%s.percentage', $budgetItemBase)] = 'numeric|max:100';
            $rules[sprintf('%s.%s', $budgetItemBase, $code)]  = sprintf('required|in:%s', $this->validCodeList('BudgetIdentifier', 'V201'));
            $rules                                            = array_merge(
                $rules,
                $this->getBudgetItemDescriptionRules(getVal($budgetItem, ['description'], []), $budgetItemBase)
            );
            $rules                                            = array_merge(
                $rules,
                $this->getRulesForBudgetPercentage($countryBudgetItems)
            );
        }

        return $rules;
    }

    /**
     * return budget item error message
     * @param       $budgetItems
     * @param       $countryBudgetItemBase
     * @param       $code
     * @return array
     */
    public function getBudgetItemMessages($budgetItems, $countryBudgetItemBase, $code)
    {
        $messages = [];
        foreach ($budgetItems as $budgetItemIndex => $budgetItem) {
            $budgetItemBase                                                    = sprintf('%s.budget_item.%s', $countryBudgetItemBase, $budgetItemIndex);
            $messages[sprintf('%s.%s.required', $budgetItemBase, $code)]       = 'Code is required in Budget Item of Country Budget Items';
            $messages[sprintf('%s.%s.in', $budgetItemBase, $code)]             = 'Budget Item Code is incorrect in Country Budget Items';
            $messages[sprintf('%s.percentage.%s', $budgetItemBase, 'numeric')] = 'Percentage should be numeric';
            $messages[sprintf('%s.percentage.%s', $budgetItemBase, 'max')]     = 'Percentage should less than or equal to :max';
            $messages[sprintf('%s.percentage.sum', $budgetItemBase)]           = 'Total percentage of budget items under the same vocabulary must be equal to 100.';
            $messages[sprintf('%s.percentage.required', $budgetItemBase)]      = 'Percentage is required when there are multiple codes  .';
            $messages[sprintf('%s.percentage.total', $budgetItemBase)]         = 'Percentage should be 100 when there is only one budget item.';
            $messages                                                          = array_merge(
                $messages,
                $this->getBudgetItemDescriptionMessages(getVal($budgetItem, ['description'], []), $budgetItemBase)
            );
        }

        return $messages;
    }

    /**
     * return budget item description rule
     * @param $descriptions
     * @param $budgetItemBase
     * @return array
     */
    public function getBudgetItemDescriptionRules($descriptions, $budgetItemBase)
    {
        $rules = [];
        foreach ($descriptions as $descriptionIndex => $description) {
            $descriptionBase = sprintf('%s.description.%s', $budgetItemBase, $descriptionIndex);
            $rules           = $this->factory->getRulesForNarrative($description['narrative'], $descriptionBase);
        }

        return $rules;
    }

    /**
     * return budget item description error message
     * @param $descriptions
     * @param $budgetItemBase
     * @return array
     */
    public function getBudgetItemDescriptionMessages($descriptions, $budgetItemBase)
    {
        $messages = [];
        foreach ($descriptions as $descriptionIndex => $description) {
            $descriptionBase = sprintf('%s.description.%s', $budgetItemBase, $descriptionIndex);
            $messages        = $this->factory->getMessagesForNarrative($description['narrative'], $descriptionBase);
        }

        return $messages;
    }

    /** Returns rules for percentage
     * @param $countryBudget
     * @return array
     */
    protected function getRulesForBudgetPercentage($countryBudget)
    {
        $countryBudgetItems      = getVal($countryBudget, [0, 'budget_item'], []);
        $totalPercentage         = 0;
        $isEmpty                 = false;
        $countryBudgetPercentage = 0;
        $rules                   = [];

        if (count($countryBudgetItems) > 1) {
            foreach ($countryBudgetItems as $key => $countryBudgetItem) {
                ($countryBudgetItem['percentage'] != "") ? $countryBudgetPercentage = $countryBudgetItem['percentage'] : $isEmpty = true;
                $totalPercentage = $totalPercentage + $countryBudgetPercentage;
            }

            foreach ($countryBudgetItems as $key => $countryBudgetItem) {
                if ($isEmpty) {
                    $rules["country_budget_items.0.budget_item.$key.percentage"] = 'required';
                } elseif ($totalPercentage != 100) {
                    $rules["country_budget_items.0.budget_item.$key.percentage"] = 'sum';
                }
            }
        } else {
            $rules["country_budget_items.0.budget_item.0.percentage"] = 'total';
        }

        return $rules;
    }

    /**
     * returns rules for HumanitarianScope
     * @param array $activity
     * @return array|mixed
     */
    public function rulesForHumanitarianScope(array $activity)
    {
        $rules              = [];
        $humanitarianScopes = getVal($activity, ['humanitarian_scope'], []);

        foreach ($humanitarianScopes as $humanitarianScopeIndex => $humanitarianScope) {
            $humanitarianScopeBase                             = 'humanitarian_scope.' . $humanitarianScopeIndex;
            $rules[$humanitarianScopeBase . '.type']           = sprintf('required|in:%s', $this->validCodeList('HumanitarianScopeType', 'V202'));
            $rules[$humanitarianScopeBase . '.vocabulary']     = sprintf('required|in:%s', $this->validCodeList('HumanitarianScopeVocabulary', 'V202'));
            $rules[$humanitarianScopeBase . '.vocabulary_uri'] = 'url';
            $rules[$humanitarianScopeBase . '.code']           = 'required|string';
            $rules                                             = array_merge($rules, $this->factory->getRulesForNarrative(getVal($humanitarianScope, ['narrative'], []), $humanitarianScopeBase));
        }

        return $rules;
    }

    /**
     * Returns messages for HumanitarianScope.
     *
     * @param array $activity
     * @return array|mixed
     */
    public function messagesForHumanitarianScope(array $activity)
    {
        $messages           = [];
        $humanitarianScopes = getVal($activity, ['humanitarian_scope'], []);

        foreach ($humanitarianScopes as $humanitarianScopeIndex => $humanitarianScope) {
            $humanitarianScopeForm                                     = 'humanitarian_scope.' . $humanitarianScopeIndex;
            $messages[$humanitarianScopeForm . '.type.required']       = 'Humanitarian Scope type is required';
            $messages[$humanitarianScopeForm . '.type.in']             = 'Humanitarian Scope type is invalid';
            $messages[$humanitarianScopeForm . '.vocabulary.required'] = 'Humanitarian Scope vocabulary is required';
            $messages[$humanitarianScopeForm . '.vocabulary.in']       = 'Humanitarian Scope vocabulary is invalid';
            $messages[$humanitarianScopeForm . '.code.required']       = 'Humanitarian Scope code is required';
            $messages[$humanitarianScopeForm . '.code.string']         = 'Humanitarian Scope should be string';
            $messages[$humanitarianScopeForm . '.vocabulary_uri.url']  = 'Enter valid URL. eg. http://example.com';
            $messages                                                  = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative(getVal($humanitarianScope, ['narrative'], []), $humanitarianScopeForm)
            );
        }

        return $messages;
    }

    /**
     * Get rules for Policy Marker.
     *
     * @param array $activity
     * @return array
     */
    public function rulesForPolicyMarker(array $activity)
    {
        $rules         = [];
        $policyMarkers = getVal($activity, ['policy_marker'], []);

        foreach ($policyMarkers as $policyMarkerIndex => $policyMarker) {
            $policyMarkerForm                                       = sprintf('policy_marker.%s', $policyMarkerIndex);
            $rules[sprintf('%s.vocabulary', $policyMarkerForm)]     = sprintf('in:%s', $this->validCodeList('PolicyMarkerVocabulary', 'V201'));
            $rules[sprintf('%s.vocabulary_uri', $policyMarkerForm)] = 'url';
            $rules[sprintf('%s.policy_marker', $policyMarkerForm)]  = sprintf('required|in:%s', $this->validCodeList('PolicyMarker', 'V201'));
            $rules[sprintf('%s.significance', $policyMarkerForm)]   = sprintf('in:%s', $this->validCodeList('PolicySignificance', 'V201'));
            $rules                                                  = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($policyMarker['narrative'], $policyMarkerForm)
            );
        }

        return $rules;
    }

    /**
     * Get messages for PolicyMarker.
     *
     * @param array $activity
     * @return array
     */
    public function messagesForPolicyMarker(array $activity)
    {
        $messages      = [];
        $policyMarkers = getVal($activity, ['policy_marker'], []);

        foreach ($policyMarkers as $policyMarkerIndex => $policyMarker) {
            $policyMarkerForm                                                  = sprintf('policy_marker.%s', $policyMarkerIndex);
            $messages[sprintf('%s.vocabulary.in', $policyMarkerForm)]          = 'Entered vocabulary in Policy Marker is invalid';
            $messages[sprintf('%s.vocabulary_uri.url', $policyMarkerForm)]     = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('%s.policy_marker.required', $policyMarkerForm)] = 'Policy Marker is required';
            $messages[sprintf('%s.policy_marker.in', $policyMarkerForm)]       = 'Entered code in Policy Marker is invalid';
            $messages[sprintf('%s.significance.in', $policyMarkerForm)]        = 'Entered significance code in Policy Marker is invalid';
            $messages                                                          = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($policyMarker['narrative'], $policyMarkerForm)
            );
        }

        return $messages;
    }

    /**
     * Get rules for Budget.
     *
     * @param array $activity
     * @return array
     */
    protected function rulesForBudget(array $activity)
    {
        $rules   = [];
        $budgets = getVal($activity, ['budget'], []);

        foreach ($budgets as $budgetIndex => $budget) {
            $budgetBase                                    = sprintf('budget.%s', $budgetIndex);
            $rules[sprintf('%s.status', $budgetBase)]      = sprintf('required|in:%s', $this->validCodeList('BudgetStatus', 'V202'));
            $rules[sprintf('%s.budget_type', $budgetBase)] = sprintf('in:%s', $this->validCodeList('BudgetType', 'V201'));
            $rules                                         = array_merge(
                $rules,
                $this->factory->getRulesForPeriodStart($budget['period_start'], $budgetBase),
                $this->factory->getRulesForPeriodEnd($budget['period_end'], $budgetBase),
                $this->getRulesForValue($budget['value'], $budgetBase)
            );

            $startDate = getVal($budget, ['period_start', 0, 'date']);
            $newDate   = $startDate ? date('Y-m-d', strtotime($startDate . '+1year')) : '';
            if ($newDate) {
                $rules[$budgetBase . '.period_end.0.date'][] = sprintf('before:%s', $newDate);
            }

        }

        return $rules;
    }

    /**
     * Get messages for Budget.
     *
     * @param array $activity
     * @return array
     */
    protected function messagesForBudget(array $activity)
    {
        $messages = [];
        $budgets  = getVal($activity, ['budget'], []);

        foreach ($budgets as $budgetIndex => $budget) {
            $budgetBase = sprintf('budget.%s', $budgetIndex);

            $messages[sprintf('%s.status.required', $budgetBase)] = 'Budget Status is required.';
            $messages[sprintf('%s.status.in', $budgetBase)]       = 'Budget Status is invalid.';
            $messages[sprintf('%s.budget_type.in', $budgetBase)]  = 'Budget Type is invalid.';
            $messages                                             = array_merge(
                $messages,
                $this->factory->getMessagesForPeriodStart($budget['period_start'], $budgetBase),
                $this->factory->getMessagesForPeriodEnd($budget['period_end'], $budgetBase),
                $this->getMessagesForValue($budget['value'], $budgetBase)
            );
            $messages[$budgetBase . '.period_end.0.date.before']  = 'Period End must not be more than a year from Period Start';

        }

        return $messages;
    }

    /**
     * @param $budgetValues
     * @param $budgetBase
     * @return array
     */
    protected function getRulesForValue($budgetValues, $budgetBase)
    {
        $rules = [];
        foreach ($budgetValues as $valueIndex => $value) {
            $valueBase                                   = sprintf('%s.value.%s', $budgetBase, $valueIndex);
            $rules[sprintf('%s.amount', $valueBase)]     = 'required|numeric';
            $rules[sprintf('%s.value_date', $valueBase)] = 'required';
        }

        return $rules;
    }

    /**
     * @param $budgetValues
     * @param $budgetBase
     * @return array
     */
    protected function getMessagesForValue($budgetValues, $budgetBase)
    {
        $messages = [];
        foreach ($budgetValues as $valueIndex => $value) {
            $valueBase                                               = sprintf('%s.value.%s', $budgetBase, $valueIndex);
            $messages[sprintf('%s.amount.required', $valueBase)]     = 'Amount is required in Budget';
            $messages[sprintf('%s.amount.numeric', $valueBase)]      = 'Amount should be numeric in Budget';
            $messages[sprintf('%s.value_date.required', $valueBase)] = 'Date is required in Budget';
        }

        return $messages;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function rulesForPlannedDisbursement(array $activity)
    {
        $rules                = [];
        $plannedDisbursements = getVal($activity, ['planned_disbursement'], []);

        foreach ($plannedDisbursements as $plannedDisbursementIndex => $plannedDisbursement) {
            $plannedDisbursementBase                                                  = sprintf('planned_disbursement.%s', $plannedDisbursementIndex);
            $rules[sprintf('%s.planned_disbursement_type', $plannedDisbursementBase)] = sprintf('in:%s', $this->validCodeList('BudgetType', 'V201'));

            $rules = array_merge(
                $rules,
                $this->factory->getRulesForPeriodStart($plannedDisbursement['period_start'], $plannedDisbursementBase),
                $this->factory->getRulesForPeriodEnd($plannedDisbursement['period_end'], $plannedDisbursementBase),
                $this->getRulesForValue($plannedDisbursement['value'], $plannedDisbursementBase),
                $this->getRulesForProviderOrg($plannedDisbursement['provider_org'], $plannedDisbursementBase),
                $this->getRulesForReceiverOrg($plannedDisbursement['receiver_org'], $plannedDisbursementBase)
            );
        }

        return $rules;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function messagesForPlannedDisbursement(array $activity)
    {
        $messages             = [];
        $plannedDisbursements = getVal($activity, ['planned_disbursement'], []);

        foreach ($plannedDisbursements as $plannedDisbursementIndex => $plannedDisbursement) {
            $plannedDisbursementBase                                                     = sprintf('planned_disbursement.%s', $plannedDisbursementIndex);
            $rules[sprintf('%s.planned_disbursement_type.in', $plannedDisbursementBase)] = 'Planned Disbursement Type is incorrect';

            $messages = array_merge(
                $messages,
                $this->factory->getMessagesForPeriodStart($plannedDisbursement['period_start'], $plannedDisbursementBase),
                $this->factory->getMessagesForPeriodEnd($plannedDisbursement['period_end'], $plannedDisbursementBase),
                $this->getMessagesForValue($plannedDisbursement['value'], $plannedDisbursementBase),
                $this->getMessagesForProviderOrg($plannedDisbursement['provider_org'], $plannedDisbursementBase),
                $this->getMessagesForReceiverOrg($plannedDisbursement['receiver_org'], $plannedDisbursementBase)
            );
        }

        return $messages;
    }

    /**
     * @param array $providerOrgData
     * @param       $plannedDisbursementBase
     * @return array
     */
    protected function getRulesForProviderOrg(array $providerOrgData, $plannedDisbursementBase)
    {
        $rules = [];

        foreach ($providerOrgData as $providerOrgIndex => $providerOrg) {
            $providerOrgBase                             = sprintf('%s.provider_org.%s', $plannedDisbursementBase, $providerOrgIndex);
            $rules[sprintf('%s.type', $providerOrgBase)] = sprintf('in:%s', $this->validCodeList('OrganisationType', 'V201'));
            $rules                                       = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($providerOrg['narrative'], $providerOrgBase)
            );
        }

        return $rules;
    }

    /**
     * @param array $providerOrgData
     * @param       $plannedDisbursementBase
     * @return array
     */
    protected function getMessagesForProviderOrg(array $providerOrgData, $plannedDisbursementBase)
    {
        $message = [];

        foreach ($providerOrgData as $providerOrgIndex => $providerOrg) {
            $providerOrgBase                                  = sprintf('%s.provider_org.%s', $plannedDisbursementBase, $providerOrgIndex);
            $message[sprintf('%s.type.in', $providerOrgBase)] = 'Entered organisation type in provider org is incorrect';
            $message                                          = array_merge(
                $message,
                $this->factory->getMessagesForNarrative($providerOrg['narrative'], $providerOrgBase)
            );
        }

        return $message;
    }

    /**
     * @param array $receiverOrgData
     * @param       $plannedDisbursementBase
     * @return array
     */
    protected function getRulesForReceiverOrg(array $receiverOrgData, $plannedDisbursementBase)
    {
        $rules = [];

        foreach ($receiverOrgData as $receiverOrgIndex => $receiverOrg) {
            $receiverOrgBase                             = sprintf('%s.receiver_org.%s', $plannedDisbursementBase, $receiverOrgIndex);
            $rules[sprintf('%s.type', $receiverOrgBase)] = sprintf('in:%s', $this->validCodeList('OrganisationType', 'V201'));
            $rules                                       = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($receiverOrg['narrative'], $receiverOrgBase)
            );
        }

        return $rules;
    }

    /**
     * @param array $receiverOrgData
     * @param       $plannedDisbursementBase
     * @return array
     */
    protected function getMessagesForReceiverOrg(array $receiverOrgData, $plannedDisbursementBase)
    {
        $message = [];

        foreach ($receiverOrgData as $receiverOrgIndex => $receiverOrg) {
            $receiverOrgBase                                  = sprintf('%s.receiver_org.%s', $plannedDisbursementBase, $receiverOrgIndex);
            $message[sprintf('%s.type.in', $receiverOrgBase)] = 'Entered organisation type in receiver org is incorrect';
            $message                                          = array_merge(
                $message,
                $this->factory->getMessagesForNarrative($receiverOrg['narrative'], $receiverOrgBase)
            );
        }

        return $message;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function rulesForDocumentLink(array $activity)
    {
        $rules         = [];
        $documentLinks = getVal($activity, ['document_links'], []);

        foreach ($documentLinks as $documentLinkIndex => $documentLink) {
            $documentLinkBase                                                                      = sprintf('document_links.%s', $documentLinkIndex);
            $rules[sprintf('%s.document_link.url', $documentLinkBase)]                             = 'required|url';
            $rules[sprintf('%s.document_link.format', $documentLinkBase)]                          = sprintf('required|in:%s', $this->validCodeList('FileFormat', 'V201'));
            $rules[sprintf('%s.document_link.language.*.language', $documentLinkBase)]             = sprintf('in:%s', $this->validCodeList('Language', 'V201'));
            $rules                                                                                 = array_merge(
                $rules,
                $this->factory->getRulesForNarrative(getVal($documentLink, ['document_link', 'title', 0, 'narrative'], []), sprintf('%s.document_link.title.0', $documentLinkBase)),
                $this->getRulesForDocumentCategory(getVal($documentLink, ['document_link', 'category'], []), sprintf('%s.document_link', $documentLinkBase))
            );
            $rules[sprintf('%s.document_link.title.0.narrative.0.narrative', $documentLinkBase)][] = 'required';
        }


        return $rules;

    }

    /**
     * @param array $activity
     * @return array
     */
    protected function messagesForDocumentLink(array $activity)
    {
        $messages      = [];
        $documentLinks = getVal($activity, ['document_link'], []);

        foreach ($documentLinks as $documentLinkIndex => $documentLink) {
            $documentLinkBase                                                                                = sprintf('document_link.%s', $documentLinkIndex);
            $messages[sprintf('document_link.%s.url.required', $documentLinkIndex)]                          = 'Url is required';
            $messages[sprintf('document_link.%s.url.url', $documentLinkIndex)]                               = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('document_link.%s.format.required', $documentLinkIndex)]                       = 'Format is required';
            $messages[sprintf('document_link.%s.format.in', $documentLinkIndex)]                             = 'Enter document format type is incorrect';
            $messages[sprintf('document_link.%s.language.*.language.in', $documentLinkIndex)]                = 'Enter language code is incorrect';
            $messages                                                                                        = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative(getVal($documentLink, ['document_link', 'title', 0, 'narrative'], []), sprintf('%s.document_link.title.0', $documentLinkBase)),
                $this->getMessagesForDocumentCategory(getVal($documentLink, ['document_link', 'category'], []), sprintf('%s.document_link', $documentLinkBase))
            );
            $messages[sprintf('%s.document_link.title.0.narrative.0.narrative.required', $documentLinkBase)] = 'Narrative is required.';
        }

        return $messages;

    }

    /**
     * @param $categories
     * @param $documentLinkBase
     * @return array
     */
    protected function getRulesForDocumentCategory($categories, $documentLinkBase)
    {
        $rules = [];
        foreach ($categories as $documentCategoryIndex => $documentCategory) {
            $rules[sprintf('%s.category.%s.code', $documentLinkBase, $documentCategoryIndex)] = sprintf('required|in:%s', $this->validCodeList('DocumentCategory', 'V201'));
        }

        return $rules;
    }

    /**
     * @param $categories
     * @param $documentLinkBase
     * @return array
     */
    protected function getMessagesForDocumentCategory($categories, $documentLinkBase)
    {
        $messages = [];
        foreach ($categories as $documentCategoryIndex => $documentCategory) {
            $messages[sprintf('%s.category.%s.code.required', $documentLinkBase, $documentCategoryIndex)] = 'Category is required';
            $messages[sprintf('%s.category.%s.code.in', $documentLinkBase, $documentCategoryIndex)]       = 'Category is incorrect';
        }

        return $messages;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function rulesForRelatedActivity(array $activity)
    {
        $rules             = [];
        $relatedActivities = getVal($activity, ['related_activity'], []);

        foreach ($relatedActivities as $relatedActivityIndex => $relatedActivity) {
            $relatedActivityBase                                            = sprintf('related_activity.%s', $relatedActivityIndex);
            $rules[sprintf('%s.relationship_type', $relatedActivityBase)]   = sprintf('required|in:%s', $this->validCodeList('RelatedActivityType', 'V201'));
            $rules[sprintf('%s.activity_identifier', $relatedActivityBase)] = 'required';
        }

        return $rules;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function messagesForRelatedActivity(array  $activity)
    {
        $messages          = [];
        $relatedActivities = getVal($activity, ['related_activity'], []);

        foreach ($relatedActivities as $relatedActivityIndex => $relatedActivity) {
            $relatedActivityBase                                                        = sprintf('related_activity.%s', $relatedActivityIndex);
            $messages[sprintf('%s.relationship_type.required', $relatedActivityBase)]   = 'Type of Relationship is required';
            $messages[sprintf('%s.relationship_type.in', $relatedActivityBase)]         = 'Type of Relationship is incorrect';
            $messages[sprintf('%s.activity_identifier.required', $relatedActivityBase)] = 'Activity Identifier is required';
        }

        return $messages;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function rulesForLegacyData(array $activity)
    {
        $rules  = [];
        $legacy = getVal($activity, ['legacy_data'], []);

        foreach ($legacy as $legacyDataIndex => $legacyData) {
            $legacyDataBase                              = sprintf('legacy_data.%s', $legacyDataIndex);
            $rules[sprintf('%s.name', $legacyDataBase)]  = 'required';
            $rules[sprintf('%s.value', $legacyDataBase)] = 'required';
        }

        return $rules;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function messagesForLegacyData(array $activity)
    {
        $messages = [];
        $legacy   = getVal($activity, ['legacy_data'], []);

        foreach ($legacy as $legacyDataIndex => $legacyData) {
            $legacyDataBase                                          = sprintf('legacy_data.%s', $legacyDataIndex);
            $messages[sprintf('%s.name.required', $legacyDataBase)]  = 'Name is Required';
            $messages[sprintf('%s.value.required', $legacyDataBase)] = 'Value is Required';
        }

        return $messages;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function rulesForCondition(array $activity)
    {
        $rules      = [];
        $conditions = getVal($activity, ['conditions'], []);

        $rules['conditions.condition_attached'] = 'in:1,0';

        foreach (getVal($conditions, ['condition'], []) as $conditionIndex => $condition) {
            $conditionBase                                       = sprintf('conditions.condition.%s', $conditionIndex);
            $rules[sprintf('%s.condition_type', $conditionBase)] = sprintf('required_if:condition_attached,1|in:%s', $this->validCodeList('ConditionType', 'V201'));
            $rules                                               = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($condition['narrative'], $conditionBase)
            );

            foreach ($condition['narrative'] as $narrativeIndex => $narrative) {
                $rules[sprintf('%s.narrative.%s.narrative', $conditionBase, $narrativeIndex)][] = 'required_if:condition_attached,1';
            }
        }

        return $rules;
    }

    /**
     * @param array $activity
     * @return array
     */
    protected function messagesForCondition(array $activity)
    {
        $messages   = [];
        $conditions = getVal($activity, ['conditions'], []);

        $messages['conditions.condition_attached.required'] = 'Condition Attached is required';
        $messages['conditions.condition_attached.in']       = 'Condition Attached code is incorrect';

        foreach (getVal($conditions, ['condition'], []) as $conditionIndex => $condition) {
            $conditionBase                                                      = sprintf('conditions.condition.%s', $conditionIndex);
            $messages[sprintf('%s.condition_type.required_if', $conditionBase)] = 'Condition Type is required if Condition Attached is Yes';
            $messages[sprintf('%s.condition_type.required_if', $conditionBase)] = 'Condition Type code is incorrect';
            $messages                                                           = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($condition['narrative'], $conditionBase)
            );

            foreach ($condition['narrative'] as $narrativeIndex => $narrative) {
                $messages[sprintf('%s.narrative.%s.narrative.required_if', $conditionBase, $narrativeIndex)] = 'Narrative is required if Condition Attached is Yes.';
            }
        }

        return $messages;
    }

    /**
     * returns rules for transaction
     * @param $activity
     * @return array|mixed
     */
    protected function rulesForTransaction($activity)
    {
        $rules        = [];
        $transactions = getVal($activity, ['transaction'], []);

        foreach ($transactions as $transactionIndex => $transaction) {
            $references      = $this->getReferences($transactions, $transaction['id']);
            $transactionBase = sprintf('transaction.%s.transaction', $transactionIndex);

            $transactionReferences = [];
            foreach ($references as $referenceKey => $reference) {
                $transactionReferences[] = $reference;
            }

            $transactionReference                                                                        = implode(',', $transactionReferences);
            $rules[sprintf('%s.humanitarian', $transactionBase)]                                         = 'in:1,0';
            $rules[sprintf('%s.reference', $transactionBase)]                                            = 'not_in:' . $transactionReference;
            $rules[sprintf('%s.provider_organization.0.organization_identifier_code', $transactionBase)] = 'exclude_operators';
            $rules[sprintf('%s.receiver_organization.0.organization_identifier_code', $transactionBase)] = 'exclude_operators';
            $rules[sprintf('%s.recipient_country.0.country_code', $transactionBase)]                     = sprintf('in:%s', $this->validCodeList('Country', 'V201', 'Organization'));
            $rules[sprintf('%s.recipient_region.0.region_code', $transactionBase)]                       = sprintf('in:%s', $this->validCodeList('Region', 'V201'));
            $rules[sprintf('%s.recipient_region.0.vocabulary', $transactionBase)]                        = sprintf('in:%s', $this->validCodeList('RegionVocabulary', 'V202'));
            $rules[sprintf('%s.flow_type.0.flow_type', $transactionBase)]                                = sprintf('in:%s', $this->validCodeList('FlowType', 'V201'));
            $rules[sprintf('%s.finance_type.0.finance_type', $transactionBase)]                          = sprintf('in:%s', $this->validCodeList('FinanceType', 'V201'));
            $rules[sprintf('%s.aid_type.0.aid_type', $transactionBase)]                                  = sprintf('in:%s', $this->validCodeList('AidType', 'V201'));
            $rules[sprintf('%s.tied_status.0.tied_status_code', $transactionBase)]                       = sprintf('in:%s', $this->validCodeList('TiedStatus', 'V201'));

            $rules = array_merge(
                $rules,
                $this->getTransactionTypeRules(getVal($transaction, ['transaction', 'transaction_type'], []), $transactionBase),
                $this->getTransactionDateRules(getVal($transaction, ['transaction', 'transaction_date'], []), $transactionBase),
                $this->getValueRules(getVal($transaction, ['transaction', 'value'], []), $transactionBase),
                $this->getDescriptionRules(getVal($transaction, ['transaction', 'description'], []), $transactionBase),
                $this->getSectorsRules(getVal($transaction, ['transaction', 'sector'], []), $transactionBase),
                $this->getRulesForTransactionProviderOrg(getVal($transaction, ['transaction', 'provider_organization'], []), $transactionBase),
                $this->getRulesForTransactionReceiverOrg(getVal($transaction, ['transaction', 'receiver_organization'], []), $transactionBase)
            );
        }

        return $rules;
    }

    /**
     * returns messages for transaction
     * @param $activity
     * @return array|mixed
     */
    protected function messagesForTransaction($activity)
    {
        $messages     = [];
        $transactions = getVal($activity, ['transaction'], []);

        foreach ($transactions as $transactionIndex => $transaction) {
            $transactionBase                                                               = sprintf('transaction.%s.transaction', $transactionIndex);
            $messages[sprintf('%s.reference.not_in', $transactionBase)]                    = 'Reference should be unique';
            $messages[sprintf('%s.humanitarian.in', $transactionBase)]                     = 'Invalid humanitarian code';
            $messages[sprintf('%s.recipient_country.0.country_code.in', $transactionBase)] = 'Recipient Country Code is incorrect';
            $messages[sprintf('%s.recipient_region.0.region_code.in', $transactionBase)]   = 'Recipient Region Code is incorrect';
            $messages[sprintf('%s.recipient_region.0.vocabulary.in', $transactionBase)]    = 'Recipient Region Vocabulary is incorrect';
            $messages[sprintf('%s.flow_type.0.flow_type.in', $transactionBase)]            = 'Flow Type is incorrect';
            $messages[sprintf('%s.finance_type.0.finance_type.in', $transactionBase)]      = 'Finance Type is incorrect';
            $messages[sprintf('%s.aid_type.0.aid_type.in', $transactionBase)]              = 'Aid Type is incorrect';
            $messages[sprintf('%s.tied_status.0.tied_status_code.in', $transactionBase)]   = 'Tied Status is incorrect';

            $messages = array_merge(
                $messages,
                $this->getTransactionTypeMessages(getVal($transaction, ['transaction', 'transaction_type'], []), $transactionBase),
                $this->getTransactionDateMessages(getVal($transaction, ['transaction', 'transaction_date'], []), $transactionBase),
                $this->getValueMessages(getVal($transaction, ['transaction', 'value'], []), $transactionBase),
                $this->getDescriptionMessages(getVal($transaction, ['transaction', 'description'], []), $transactionBase),
                $this->getSectorsMessages(getVal($transaction, ['transaction', 'sector'], []), $transactionBase),
                $this->getMessagesForTransactionProviderOrg(getVal($transaction, ['transaction', 'provider_organization'], []), $transactionBase),
                $this->getMessagesForTransactionReceiverOrg(getVal($transaction, ['transaction', 'receiver_organization'], []), $transactionBase)
            );
        }

        return $messages;
    }

    /**
     * @param $transactions
     * @param $transactionId
     * @return array
     */
    protected function getReferences($transactions, $transactionId)
    {
        $references = [];
        foreach ($transactions as $transaction) {
            if (getVal($transaction, ['id'], '') != $transactionId) {
                $references[] = getVal($transaction, ['transaction', 'reference']);
            }
        }

        return $references;
    }

    /**
     * @param     array $providers
     * @param           $transactionBase
     * @return array
     */
    protected function getRulesForTransactionProviderOrg(array $providers, $transactionBase)
    {
        $rules = [];

        foreach ($providers as $providerOrgIndex => $providerOrg) {
            $providerOrgBase = sprintf('%s.provider_organization.%s', $transactionBase, $providerOrgIndex);
            $rules           = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($providerOrg['narrative'], $providerOrgBase)
            );
        }

        return $rules;
    }

    /**
     * @param   array $providers
     * @param         $transactionBase
     * @return array
     */
    protected function getMessagesForTransactionProviderOrg(array $providers, $transactionBase)
    {
        $message = [];

        foreach ($providers as $providerOrgIndex => $providerOrg) {
            $providerOrgBase = sprintf('%s.provider_organization.%s', $transactionBase, $providerOrgIndex);
            $message         = array_merge(
                $message,
                $this->factory->getMessagesForNarrative($providerOrg['narrative'], $providerOrgBase)
            );
        }

        return $message;
    }

    /**
     * @param       $receivers
     * @param       $transactionBase
     * @return array
     */
    protected function getRulesForTransactionReceiverOrg(array $receivers, $transactionBase)
    {
        $rules = [];

        foreach ($receivers as $receiverOrgIndex => $receiverOrg) {
            $receiverOrgBase = sprintf('%s.receiver_organization.%s', $transactionBase, $receiverOrgIndex);
            $rules           = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($receiverOrg['narrative'], $receiverOrgBase)
            );
        }

        return $rules;
    }

    /**
     * @param array $receivers
     * @param       $transactionBase
     * @return array
     */
    protected function getMessagesForTransactionReceiverOrg(array $receivers, $transactionBase)
    {
        $message = [];

        foreach ($receivers as $receiverOrgIndex => $receiverOrg) {
            $receiverOrgBase = sprintf('%s.receiver_organization.%s', $transactionBase, $receiverOrgIndex);
            $message         = array_merge(
                $message,
                $this->factory->getMessagesForNarrative($receiverOrg['narrative'], $receiverOrgBase)
            );
        }

        return $message;
    }

    /**
     * returns rules for sector
     * @param $sectors
     * @param $transactionBase
     * @return array|mixed
     */
    public function getSectorsRules($sectors, $transactionBase)
    {
        $rules = [];

        foreach ($sectors as $sectorIndex => $sector) {
            $sectorBase                                             = sprintf('%s.sector.%s', $transactionBase, $sectorIndex);
            $rules[sprintf('%s.vocabulary_uri', $sectorBase)]       = 'url';
            $rules[sprintf('%s.vocabulary', $sectorBase)]           = sprintf('in:%s', $this->validCodeList('SectorVocabulary', 'V202'));
            $rules[sprintf('%s.sector_code', $sectorBase)]          = sprintf('in:%s', $this->validCodeList('Sector', 'V201'));
            $rules[sprintf('%s.sector_category_code', $sectorBase)] = sprintf('in:%s', $this->validCodeList('SectorCategory', 'V201'));

            if ($sector['sector_vocabulary'] == 1) {
                $rules[sprintf('%s.sector_code', $sectorBase)]       = sprintf('in:%s|required_with:' . $sectorBase . '.sector_vocabulary', $this->validCodeList('Sector', 'V201'));
                $rules[sprintf('%s.sector_vocabulary', $sectorBase)] = sprintf('in:%s|required_with:' . $sectorBase . '.sector_code', $this->validCodeList('SectorVocabulary', 'V202'));
            } elseif ($sector['sector_vocabulary'] == 2) {
                $rules[sprintf('%s.sector_category_code', $sectorBase)] = sprintf('in:%s|required_with:' . $sectorBase . '.sector_vocabulary', $this->validCodeList('SectorCategory', 'V201'));
                $rules[sprintf('%s.sector_vocabulary', $sectorBase)]    = sprintf('in:%s|required_with:' . $sectorBase . '.sector_category_code', $this->validCodeList('SectorVocabulary', 'V202'));
            } elseif ($sector['sector_vocabulary'] != "") {
                $rules[sprintf('%s.sector_text', $sectorBase)]       = 'required_with:' . $sectorBase . '.sector_vocabulary';
                $rules[sprintf('%s.sector_vocabulary', $sectorBase)] = sprintf('in:%s|required_with:' . $sectorBase . '.sector_text', $this->validCodeList('SectorVocabulary', 'V202'));
            }
        }
        $rules = array_merge($rules, $this->factory->getRulesForTransactionSectorNarrative($sector, $sector['narrative'], $sectorBase));

        return $rules;
    }

    /**
     * returns messages for sector
     * @param $sectors
     * @param $transactionBase
     * @return array|mixed
     */
    public function getSectorsMessages($sectors, $transactionBase)
    {
        $messages = [];

        foreach ($sectors as $sectorIndex => $sector) {
            $sectorBase                                                = sprintf('%s.sector.%s', $transactionBase, $sectorIndex);
            $messages[sprintf('%s.vocabulary_uri.url', $sectorBase)]   = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('%s.vocabulary', $sectorBase)]           = 'Sector vocabulary is incorrect';
            $messages[sprintf('%s.sector_code', $sectorBase)]          = 'Sector code is incorrect for sector vocabulary 1';
            $messages[sprintf('%s.sector_category_code', $sectorBase)] = 'Sector code is incorrect for sector vocabulary 2';

            if ($sector['sector_vocabulary'] == 1) {
                $messages[sprintf('%s.sector_code.%s', $sectorBase, 'required_with')]       = 'Sector code is required with Sector vocabulary.';
                $messages[sprintf('%s.sector_vocabulary.%s', $sectorBase, 'required_with')] = 'Sector vocabulary is required with Sector code.';
            } elseif ($sector['sector_vocabulary'] == 2) {
                $messages[sprintf('%s.sector_category_code.%s', $sectorBase, 'required_with')] = 'Sector code is required with Sector vocabulary.';
                $messages[sprintf('%s.sector_vocabulary.%s', $sectorBase, 'required_with')]    = 'Sector vocabulary is required with Sector code.';
            } elseif ($sector['sector_vocabulary'] != "") {
                $messages[sprintf('%s.sector_text.%s', $sectorBase, 'required_with')]       = 'Sector code is required with Sector vocabulary.';
                $messages[sprintf('%s.sector_vocabulary.%s', $sectorBase, 'required_with')] = 'Sector vocabulary is required with Sector code.';
            }

        }
        $messages = array_merge($messages, $this->factory->getMessagesForTransactionSectorNarrative($sector, $sector['narrative'], $sectorBase));


        return $messages;
    }

    /**
     * returns rules for recipient region
     * @param $recipientRegions
     * @param $transactionBase
     * @return array|mixed
     */
    public function getRecipientRegionRules($recipientRegions, $transactionBase)
    {
        $rules = [];

        foreach ($recipientRegions as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionBase                             = sprintf('%s.recipient_region.%s', $transactionBase, $recipientRegionIndex);
            $rules[$recipientRegionBase . '.region_code']    = 'required';
            $rules[$recipientRegionBase . '.vocabulary_uri'] = 'url';
            $rules                                           = array_merge($rules, $this->factory->getRulesForNarrative($recipientRegion['narrative'], $recipientRegionBase));
        }

        return $rules;
    }

    /**
     * returns messages for recipient region
     * @param $recipientRegions
     * @param $transactionBase
     * @return array|mixed
     */
    public function getRecipientRegionMessages($recipientRegions, $transactionBase)
    {
        $messages = [];

        foreach ($recipientRegions as $recipientRegionIndex => $recipientRegion) {
            $recipientRegionBase                                      = sprintf('%s.recipient_region.%s', $transactionBase, $recipientRegionIndex);
            $messages[$recipientRegionBase . '.region_code.required'] = 'Recipient region code is required';
            $messages[$recipientRegionBase . '.vocabulary_uri.url']   = 'Enter valid URL. eg. http://example.com';
            $messages                                                 = array_merge($messages, $this->factory->getMessagesForNarrative($recipientRegion['narrative'], $recipientRegionBase));
        }

        return $messages;
    }

    /**
     * get transaction type rules
     * @param $types
     * @param $transactionBase
     * @return array
     */
    protected function getTransactionTypeRules($types, $transactionBase)
    {
        $rules = [];

        foreach ($types as $typeIndex => $type) {
            $typeBase                                                          = sprintf('%s.transaction_type.%s', $transactionBase, $typeIndex);
            $rules[sprintf('%s.transaction_type_code', $typeBase, $typeIndex)] = sprintf('required|in:%s', $this->validCodeList('TransactionType', 'V202'));
        }

        return $rules;
    }

    /**
     * get transaction type error message
     * @param $types
     * @param $transactionBase
     * @return array
     */
    protected function getTransactionTypeMessages($types, $transactionBase)
    {
        $messages = [];

        foreach ($types as $typeIndex => $type) {
            $typeBase                                                          = sprintf('%s.transaction_type.%s', $transactionBase, $typeIndex);
            $messages[sprintf('%s.transaction_type_code.required', $typeBase)] = 'Transaction type is required';
            $messages[sprintf('%s.transaction_type_code.in', $typeBase)]       = 'Transaction type is incorrect';
        }

        return $messages;
    }

    /**
     * get transaction date rules
     * @param $transactionDate
     * @param $transactionBase
     * @return array
     */
    protected function getTransactionDateRules($transactionDate, $transactionBase)
    {
        $rules = [];
        foreach ($transactionDate as $dateIndex => $date) {
            $dateBase                             = sprintf('%s.transaction_date.%s', $transactionBase, $dateIndex);
            $rules[sprintf('%s.date', $dateBase)] = 'required';
        }

        return $rules;
    }

    /**
     * get transaction date error message
     * @param $transactionDate
     * @param $transactionBase
     * @return array
     */
    protected function getTransactionDateMessages($transactionDate, $transactionBase)
    {
        $messages = [];
        foreach ($transactionDate as $dateIndex => $date) {
            $dateBase                                         = sprintf('%s.transaction_date.%s', $transactionBase, $dateIndex);
            $messages[sprintf('%s.date.required', $dateBase)] = 'Date is required';
        }

        return $messages;
    }

    /**
     * get values rules
     * @param $transactionValue
     * @param $transactionBase
     * @return array
     */
    protected function getValueRules($transactionValue, $transactionBase)
    {
        $rules = [];
        foreach ($transactionValue as $valueIndex => $value) {
            $valueBase                                = sprintf('%s.value.%s', $transactionBase, $valueIndex);
            $rules[sprintf('%s.current', $valueBase)] = sprintf('in:%s', $this->validCodeList('Currency', 'V201'));
            $rules[sprintf('%s.amount', $valueBase)]  = 'required|numeric';
            $rules[sprintf('%s.date', $valueBase)]    = 'required';
        }

        return $rules;
    }

    /**
     * get value error message
     * @param $transactionValue
     * @param $transactionBase
     * @return array
     */
    protected function getValueMessages($transactionValue, $transactionBase)
    {
        $messages = [];
        foreach ($transactionValue as $valueIndex => $value) {
            $valueBase                                           = sprintf('%s.value.%s', $transactionBase, $valueIndex);
            $messages[sprintf('%s.amount.required', $valueBase)] = 'Amount is required';
            $messages[sprintf('%s.amount.numeric', $valueBase)]  = 'Amount must be numeric';
            $messages[sprintf('%s.date.required', $valueBase)]   = 'Date is required';
        }

        return $messages;
    }

    /**
     * get description rules
     * @param $descriptions
     * @param $transactionBase
     * @return array
     */
    protected function getDescriptionRules($descriptions, $transactionBase)
    {
        $rules = [];
        foreach ($descriptions as $descriptionIndex => $description) {
            $narrativeBase = sprintf('%s.description.%s', $transactionBase, $descriptionIndex);
            $rules         = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($description['narrative'], $narrativeBase)
            );
        }

        return $rules;
    }

    /**
     * get description error message
     * @param $descriptions
     * @param $transactionBase
     * @return array
     */
    protected function getDescriptionMessages($descriptions, $transactionBase)
    {
        $messages = [];
        foreach ($descriptions as $descriptionIndex => $description) {
            $narrativeBase = sprintf('%s.description.%s', $transactionBase, $descriptionIndex);
            $messages      = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($description['narrative'], $narrativeBase)
            );
        }

        return $messages;
    }

    /**
     * returns rules for result
     * @param array $activity
     * @return array|mixed
     */
    protected function rulesForResult(array $activity)
    {
        $rules   = [];
        $results = getVal($activity, ['results'], []);

        foreach ($results as $resultIndex => $result) {
            $resultBase                             = sprintf('results.%s.result', $resultIndex);
            $rules[sprintf('%s.type', $resultBase)] = sprintf('required|in:%s', $this->validCodeList('ResultType', 'V201'));

            $rules = array_merge(
                $rules,
                $this->factory->getRulesForRequiredNarrative(getVal($result, ['result', 'title', 0, 'narrative'], []), sprintf('%s.title.0', $resultBase)),
                $this->factory->getRulesForNarrative(getVal($result, ['result', 'description', 0, 'narrative'], []), sprintf('%s.description.0', $resultBase)),
                $this->getRulesForIndicator(getVal($result, ['result', 'indicator'], []), $resultBase)
            );
        }

        return $rules;
    }

    /**
     * returns messages for result
     * @param $activity
     * @return array|mixed
     */
    protected function messagesForResult($activity)
    {
        $messages = [];
        $results  = getVal($activity, ['results'], []);

        foreach ($results as $resultIndex => $result) {
            $resultBase                                         = sprintf('results.%s.result', $resultIndex);
            $messages[sprintf('%s.type.required', $resultBase)] = 'Result Type is required.';
            $messages[sprintf('%s.type.in', $resultBase)]       = 'Result Type is incorrect.';

            $messages = array_merge(
                $messages,
                $this->factory->getMessagesForRequiredNarrative(getVal($result, ['result', 'title', 0, 'narrative'], []), sprintf('%s.title.0', $resultBase)),
                $this->factory->getMessagesForNarrative(getVal($result, ['result', 'description', 0, 'narrative'], []), sprintf('%s.description.0', $resultBase)),
                $this->getMessagesForIndicator(getVal($result, ['result', 'indicator'], []), $resultBase)
            );
        }

        return $messages;
    }

    /**
     * returns rules for indicator
     * @param $indicators
     * @param $resultBase
     * @return array|mixed
     */
    protected function getRulesForIndicator($indicators, $resultBase)
    {
        $rules = [];

        foreach ($indicators as $indicatorIndex => $indicator) {
            $indicatorBase                                  = sprintf('%s.indicator.%s', $resultBase, $indicatorIndex);
            $rules[sprintf('%s.measure', $indicatorBase)]   = sprintf('required|in:%s', $this->validCodeList('IndicatorMeasure', 'V201'));
            $rules[sprintf('%s.ascending', $indicatorBase)] = 'in:1,0';

            $rules = array_merge(
                $rules,
                $this->factory->getRulesForResultNarrative($indicator['title'], sprintf('%s.title.0', $indicatorBase)),
                $this->factory->getRulesForNarrative($indicator['description'], sprintf('%s.description.0', $indicatorBase)),
                $this->getRulesForReference($indicator['reference'], $indicatorBase),
                $this->getRulesForBaseline($indicator['baseline'], $indicatorBase),
                $this->getRulesForPeriod($indicator['period'], $indicatorBase)
            );
        }

        return $rules;
    }

    /**
     * returns messages for indicator
     * @param $indicators
     * @param $resultBase
     * @return array|mixed
     */
    protected function getMessagesForIndicator($indicators, $resultBase)
    {
        $messages = [];

        foreach ($indicators as $indicatorIndex => $indicator) {
            $indicatorBase                                            = sprintf(
                '%s.indicator.%s',
                $resultBase,
                $indicatorIndex
            );
            $messages[sprintf('%s.measure.required', $indicatorBase)] = 'Measure is required.';
            $messages                                                 = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($indicator['title'], sprintf('%s.title.0', $indicatorBase)),
                $this->getMessagesForResultNarrative($indicator['title'], sprintf('%s.title.0', $indicatorBase)),
                $this->factory->getMessagesForNarrative($indicator['description'], sprintf('%s.description.0', $indicatorBase)),
                $this->getMessagesForReference($indicator['reference'], $indicatorBase),
                $this->getMessagesForBaseline($indicator['baseline'], $indicatorBase),
                $this->getMessagesForPeriod($indicator['period'], $indicatorBase)
            );
        }

        return $messages;
    }

    /**
     * returns rules for reference
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForReference($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $referenceIndex => $reference) {
            $referenceForm                                      = sprintf(
                '%s.reference.%s',
                $formBase,
                $referenceIndex
            );
            $rules[sprintf('%s.indicator_uri', $referenceForm)] = 'url';
            $rules[sprintf('%s.vocabulary', $referenceForm)]    = sprintf('in:%s', $this->validCodeList('IndicatorVocabulary', 'V202'));
        }

        return $rules;
    }

    /**
     * returns messages for reference
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForReference($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $referenceIndex => $reference) {
            $referenceForm                                             = sprintf(
                '%s.reference.%s',
                $formBase,
                $referenceIndex
            );
            $messages[sprintf('%s.indicator_uri.url', $referenceForm)] = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('%s.vocabulary.in', $referenceForm)]     = 'Entered vocabulary for Indicator Reference is invalid';
        }

        return $messages;
    }

    /**
     * returns rules for baseline
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForBaseline($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $baselineIndex => $baseline) {
            $baselineForm                              = sprintf('%s.baseline.%s', $formBase, $baselineIndex);
            $rules[$baselineForm]                      = 'year_value_narrative_validation:' . $baselineForm . '.comment.0.narrative';
            $rules[sprintf('%s.year', $baselineForm)]  = sprintf('numeric|required_with:%s.value', $baselineForm);
            $rules[sprintf('%s.value', $baselineForm)] = sprintf('numeric|required_with:%s.year', $baselineForm);
            $rules                                     = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($baseline['comment'][0]['narrative'], sprintf('%s.comment.0', $baselineForm))
            );
        }

        return $rules;
    }

    /**
     * returns messages for baseline
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForBaseline($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $baselineIndex => $baseline) {
            $baselineForm                                                           = sprintf('%s.baseline.%s', $formBase, $baselineIndex);
            $messages[sprintf('%s.year_value_narrative_validation', $baselineForm)] = 'Year and Value is required if narrative is not empty.';
            $messages[sprintf('%s.year.required_with', $baselineForm)]              = 'Year is required with value.';
            $messages[sprintf('%s.year.numeric', $baselineForm)]                    = 'Year should be numeric.';
            $messages[sprintf('%s.value.required_with', $baselineForm)]             = 'Value is required with year.';
            $messages[sprintf('%s.value.numeric', $baselineForm)]                   = 'Value should be numeric.';
            $messages                                                               = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($baseline['comment'][0]['narrative'], sprintf('%s.comment.0', $baselineForm))
            );
        }

        return $messages;
    }

    /**
     * returns rules for period
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForPeriod($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $periodIndex => $period) {
            $periodForm = sprintf('%s.period.%s', $formBase, $periodIndex);
            $rules      = array_merge(
                $rules,
                $this->getRulesForResultPeriodStart($period['period_start'], $periodForm, $period['period_end']),
                $this->getRulesForResultPeriodEnd($period['period_end'], $periodForm, $period['period_start']),
                $this->getRulesForTarget($period['target'], sprintf('%s.target', $periodForm)),
                $this->getRulesForTarget($period['actual'], sprintf('%s.actual', $periodForm))
            );
        }

        return $rules;
    }

    /**
     * returns messages for period
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForPeriod($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $periodIndex => $period) {
            $periodForm = sprintf('%s.period.%s', $formBase, $periodIndex);
            $messages   = array_merge(
                $messages,
                $this->getMessagesForResultPeriodStart($period['period_start'], $periodForm, $period['period_end']),
                $this->getMessagesForResultPeriodEnd($period['period_end'], $periodForm, $period['period_start']),
                $this->getMessagesForTarget($period['target'], sprintf('%s.target', $periodForm)),
                $this->getMessagesForTarget($period['actual'], sprintf('%s.actual', $periodForm))
            );
        }

        return $messages;
    }

    /**
     * returns rules for target
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getRulesForTarget($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $targetIndex => $target) {
            $targetForm         = sprintf('%s.%s', $formBase, $targetIndex);
            $rules[$targetForm] = 'year_value_narrative_validation';
            $rules              = array_merge(
                $rules,
                $this->factory->getRulesForNarrative($target['comment'][0]['narrative'], sprintf('%s.comment.0', $targetForm))
            );
        }

        return $rules;
    }

    /**
     * returns messages for target
     * @param $formFields
     * @param $formBase
     * @return array|mixed
     */
    protected function getMessagesForTarget($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $targetIndex => $target) {
            $targetForm                                                           = sprintf('%s.%s', $formBase, $targetIndex);
            $messages[sprintf('%s.year_value_narrative_validation', $targetForm)] = 'Value is required if narrative is not empty.';
            $messages                                                             = array_merge(
                $messages,
                $this->factory->getMessagesForNarrative($target['comment'][0]['narrative'], sprintf('%s.comment.0', $targetForm))
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @param $periodEnd
     * @return array
     */
    protected function getRulesForResultPeriodStart($formFields, $formBase, $periodEnd)
    {
        $rules = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $periodEndLocation = $formBase . '.period_end.' . $periodStartKey . '.date';
            if ($periodEnd[$periodStartKey]['date'] != "") {
                $rules[$formBase . '.period_start.' . $periodStartKey . '.date'] = sprintf('required_with:%s|date', $periodEndLocation);
            }
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @param $periodEnd
     * @return array
     */
    protected function getMessagesForResultPeriodStart($formFields, $formBase, $periodEnd)
    {
        $messages = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            if ($periodEnd[$periodStartKey]['date'] != "") {
                $messages[$formBase . '.period_start.' . $periodStartKey . '.date.required_with'] = 'Period Start is required with Period Date';
            }
            $messages[$formBase . '.period_end.' . $periodStartKey . '.date.date'] = 'Period Start is not a valid date.';
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @param $periodStart
     * @return array
     */
    protected function getRulesForResultPeriodEnd($formFields, $formBase, $periodStart)
    {
        $rules = [];
        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $periodStartLocation = $formBase . '.period_start.' . $periodEndKey . '.date';
            if ($periodStart[$periodEndKey]['date'] != "") {
                $rules[$formBase . '.period_end.' . $periodEndKey . '.date'] = sprintf('required_with:%s|date|after:%s', $periodStartLocation, $formBase . '.period_start.' . $periodEndKey . '.date');
            }
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @param $periodStart
     * @return array
     */
    protected function getMessagesForResultPeriodEnd($formFields, $formBase, $periodStart)
    {
        $messages = [];
        foreach ($formFields as $periodEndKey => $periodEndVal) {
            if ($periodStart[$periodEndKey]['date'] != "") {
                $messages[$formBase . '.period_end.' . $periodEndKey . '.date.required_with'] = 'Period End is required with Period Start';
            }
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.date']  = 'Period End is not a valid date.';
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.after'] = 'Period End must be a date after Period Start';
        }

        return $messages;
    }

    /**
     * returns the message for indicator title.
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getMessagesForResultNarrative($formFields, $formBase)
    {
        $messages                                                 = [];
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = 'Languages should be unique.';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[sprintf(
                '%s.narrative.%s.narrative.required',
                $formBase,
                $narrativeIndex
            )] = 'Indicator Narrative is required.';
        }

        return $messages;

    }

    /**
     * Get the valid codes from the respective code list.
     * @param        $name
     * @param        $version
     * @param string $directory
     * @return string
     */
    protected function validCodeList($name, $version, $directory = "Activity")
    {
        $codeList = $this->loadCodeList($name, $version, $directory);
        $codes    = [];

        array_walk(
            $codeList[$name],
            function ($vocabulary) use (&$codes) {
                $codes[] = $vocabulary['code'];
            }
        );

        return implode(",", $codes);
    }

    /**
     * @param        $codeList
     * @param        $version
     * @param string $directory
     * @return mixed
     */
    protected function loadCodeList($codeList, $version, $directory = "Activity")
    {
        return json_decode(file_get_contents(app_path(sprintf('Core/%s/Codelist/en/%s/%s.json', $version, $directory, $codeList))), true);
    }
}