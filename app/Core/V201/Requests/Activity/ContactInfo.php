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
        return $this->getRulesForContactInfo($this->request->get('contact_info'));
    }


    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForContactInfo($this->request->get('contact_info'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForContactInfo(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $contactInfoIndex => $contactInfo) {
            $contactInfoForm = sprintf('contact_info.%s', $contactInfoIndex);
            $rules           = array_merge(
                $rules,
                $this->getRulesForDepartment($contactInfo['department'], $contactInfoForm),
                $this->getRulesForOrganization($contactInfo['organization'], $contactInfoForm),
                $this->getRulesForPersonName($contactInfo['person_name'], $contactInfoForm),
                $this->getRulesForJobTitle($contactInfo['job_title'], $contactInfoForm),
                $this->getRulesForMailingAddress($contactInfo['mailing_address'], $contactInfoForm),
                $this->getRulesForTelephone($contactInfo['telephone'], $contactInfoForm),
                $this->getRulesForEmail($contactInfo['email'], $contactInfoForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getMessagesForContactInfo(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $contactInfoIndex => $contactInfo) {
            $contactInfoForm = sprintf('contact_info.%s', $contactInfoIndex);
            $messages        = array_merge(
                $messages,
                $this->getMessagesForDepartment($contactInfo['department'], $contactInfoForm),
                $this->getMessagesForOrganization($contactInfo['organization'], $contactInfoForm),
                $this->getMessagesForPersonName($contactInfo['person_name'], $contactInfoForm),
                $this->getMessagesForJobTitle($contactInfo['job_title'], $contactInfoForm),
                $this->getMessagesForMailingAddress($contactInfo['mailing_address'], $contactInfoForm),
                $this->getMessagesForTelephone($contactInfo['telephone'], $contactInfoForm),
                $this->getMessagesForEmail($contactInfo['email'], $contactInfoForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForOrganization($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $organizationIndex => $organization) {
            $organizationForm = sprintf('%s.organization.%s', $formBase, $organizationIndex);
            $rules            = array_merge(
                $rules,
                $this->getRulesForNarrative($organization['narrative'], $organizationForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForOrganization($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $organizationIndex => $organization) {
            $organizationForm = sprintf('%s.organization.%s', $formBase, $organizationIndex);
            $messages         = array_merge(
                $messages,
                $this->getMessagesForNarrative($organization['narrative'], $organizationForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForDepartment($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $departmentIndex => $department) {
            $departmentForm = sprintf('%s.department.%s', $formBase, $departmentIndex);
            $rules          = array_merge(
                $rules,
                $this->getRulesForNarrative($department['narrative'], $departmentForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForDepartment($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $departmentIndex => $department) {
            $departmentForm = sprintf('%s.department.%s', $formBase, $departmentIndex);
            $messages       = array_merge(
                $messages,
                $this->getMessagesForNarrative($department['narrative'], $departmentForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForPersonName($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $personNameIndex => $personName) {
            $personNameForm = sprintf('%s.person_name.%s', $formBase, $personNameIndex);
            $rules          = array_merge(
                $rules,
                $this->getRulesForNarrative($personName['narrative'], $personNameForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForPersonName($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $personNameIndex => $personName) {
            $personNameForm = sprintf('%s.person_name.%s', $formBase, $personNameIndex);
            $messages       = array_merge(
                $messages,
                $this->getMessagesForNarrative($personName['narrative'], $personNameForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForJobTitle($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $jobTitleIndex => $jobTitle) {
            $jobTitleForm = sprintf('%s.job_title.%s', $formBase, $jobTitleIndex);
            $rules        = array_merge(
                $rules,
                $this->getRulesForNarrative($jobTitle['narrative'], $jobTitleForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForJobTitle($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $jobTitleIndex => $jobTitle) {
            $jobTitleForm = sprintf('%s.job_title.%s', $formBase, $jobTitleIndex);
            $messages     = array_merge(
                $messages,
                $this->getMessagesForNarrative($jobTitle['narrative'], $jobTitleForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForMailingAddress($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $mailingAddressIndex => $mailingAddress) {
            $mailingAddressForm = sprintf('%s.mailing_address.%s', $formBase, $mailingAddressIndex);
            $rules              = array_merge(
                $rules,
                $this->getRulesForNarrative($mailingAddress['narrative'], $mailingAddressForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForMailingAddress($formFields, $formBase)
    {
        $messages = [];

        foreach ($formFields as $mailingAddressIndex => $mailingAddress) {
            $mailingAddressForm = sprintf('%s.mailing_address.%s', $formBase, $mailingAddressIndex);
            $messages           = array_merge(
                $messages,
                $this->getMessagesForNarrative($mailingAddress['narrative'], $mailingAddressForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForTelephone($formFields, $formBase)
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
    public function getMessagesForTelephone($formFields, $formBase)
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
    public function getRulesForEmail($formFields, $formBase)
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
    public function getMessagesForEmail($formFields, $formBase)
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
