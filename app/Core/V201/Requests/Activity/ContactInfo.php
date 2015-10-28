<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class ContactInfo
 * @package App\Core\V201\Requests\Activity
 */
class ContactInfo extends ActivityBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->addRulesForContactInfo($this->request->get('contact_info'));
    }


    /**
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForContactInfo($this->request->get('contact_info'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addRulesForContactInfo(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $contactInfoIndex => $contactInfo) {
            $contactInfoForm = sprintf('contact_info.%s', $contactInfoIndex);
            $rules           = array_merge(
                $rules,
                $this->addRulesForDepartment($contactInfo['department'], $contactInfoForm),
                $this->addRulesForOrganization($contactInfo['organization'], $contactInfoForm),
                $this->addRulesForPersonName($contactInfo['person_name'], $contactInfoForm),
                $this->addRulesForJobTitle($contactInfo['job_title'], $contactInfoForm),
                $this->addRulesForMailingAddress($contactInfo['mailing_address'], $contactInfoForm),
                $this->addRulesForTelephone($contactInfo['telephone'], $contactInfoForm),
                $this->addRulesForEmail($contactInfo['email'], $contactInfoForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addMessagesForContactInfo(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $contactInfoIndex => $contactInfo) {
            $contactInfoForm = sprintf('contact_info.%s', $contactInfoIndex);
            $messages        = array_merge(
                $messages,
                $this->addMessagesForDepartment($contactInfo['department'], $contactInfoForm),
                $this->addMessagesForOrganization($contactInfo['organization'], $contactInfoForm),
                $this->addMessagesForPersonName($contactInfo['person_name'], $contactInfoForm),
                $this->addMessagesForJobTitle($contactInfo['job_title'], $contactInfoForm),
                $this->addMessagesForMailingAddress($contactInfo['mailing_address'], $contactInfoForm),
                $this->addMessagesForTelephone($contactInfo['telephone'], $contactInfoForm),
                $this->addMessagesForEmail($contactInfo['email'], $contactInfoForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForOrganization($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $organizationIndex => $organization) {
            $organizationForm = sprintf('%s.organization.%s', $formBase, $organizationIndex);
            $rules            = array_merge(
                $rules,
                $this->addRulesForNarrative($organization['narrative'], $organizationForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForOrganization($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $organizationIndex => $organization) {
            $organizationForm = sprintf('%s.organization.%s', $formBase, $organizationIndex);
            $messages         = array_merge(
                $messages,
                $this->addMessagesForNarrative($organization['narrative'], $organizationForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForDepartment($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $departmentIndex => $department) {
            $departmentForm = sprintf('%s.department.%s', $formBase, $departmentIndex);
            $rules          = array_merge(
                $rules,
                $this->addRulesForNarrative($department['narrative'], $departmentForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForDepartment($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $departmentIndex => $department) {
            $departmentForm = sprintf('%s.department.%s', $formBase, $departmentIndex);
            $messages       = array_merge(
                $messages,
                $this->addMessagesForNarrative($department['narrative'], $departmentForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForPersonName($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $personNameIndex => $personName) {
            $personNameForm = sprintf('%s.person_name.%s', $formBase, $personNameIndex);
            $rules          = array_merge(
                $rules,
                $this->addRulesForNarrative($personName['narrative'], $personNameForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForPersonName($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $personNameIndex => $personName) {
            $personNameForm = sprintf('%s.person_name.%s', $formBase, $personNameIndex);
            $messages       = array_merge(
                $messages,
                $this->addMessagesForNarrative($personName['narrative'], $personNameForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForJobTitle($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $jobTitleIndex => $jobTitle) {
            $jobTitleForm = sprintf('%s.job_title.%s', $formBase, $jobTitleIndex);
            $rules        = array_merge(
                $rules,
                $this->addRulesForNarrative($jobTitle['narrative'], $jobTitleForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForJobTitle($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $jobTitleIndex => $jobTitle) {
            $jobTitleForm = sprintf('%s.job_title.%s', $formBase, $jobTitleIndex);
            $messages     = array_merge(
                $messages,
                $this->addMessagesForNarrative($jobTitle['narrative'], $jobTitleForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForMailingAddress($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $mailingAddressIndex => $mailingAddress) {
            $mailingAddressForm = sprintf('%s.mailing_address.%s', $formBase, $mailingAddressIndex);
            $rules              = array_merge(
                $rules,
                $this->addRulesForNarrative($mailingAddress['narrative'], $mailingAddressForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForMailingAddress($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $mailingAddressIndex => $mailingAddress) {
            $mailingAddressForm = sprintf('%s.mailing_address.%s', $formBase, $mailingAddressIndex);
            $messages           = array_merge(
                $messages,
                $this->addMessagesForNarrative($mailingAddress['narrative'], $mailingAddressForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForTelephone($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $telephoneIndex => $telephone) {
            $rules[sprintf('%s.telephone.%s.telephone', $formBase, $telephoneIndex)] = 'numeric';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForTelephone($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $telephoneIndex => $telephone) {
            $messages[sprintf(
                '%s.telephone.%s.telephone.numeric',
                $formBase,
                $telephoneIndex
            )] = 'Telephone must be a number';
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addRulesForEmail($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $emailIndex => $email) {
            $rules[sprintf('%s.email.%s.email', $formBase, $emailIndex)] = 'email';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function addMessagesForEmail($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $emailIndex => $email) {
            $messages[sprintf(
                '%s.email.%s.email.email',
                $formBase,
                $emailIndex
            )] = 'Email must be a valid email address.';
        }

        return $messages;
    }
}
