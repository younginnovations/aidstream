<?php namespace App\Core\V201\Requests\Organization;

class CreateOrgReportingOrgRequest extends OrganizationBaseRequest
{

    protected $redirect;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForReportingOrganization($this->get('reporting_org'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForReportingOrganization(array $formFields)
    {
        $rules = [];
        foreach ($formFields as $reportingOrganizationIndex => $reportingOrganization) {
            $reportingOrganizationForm = sprintf('reporting_org.%s', $reportingOrganizationIndex);
            $rules                     = array_merge(
                $rules,
                $this->getRulesForNarrative($reportingOrganization['narrative'], $reportingOrganizationForm)
            );
        }

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForReportingOrganization($this->get('reporting_org'));
    }

    public function getMessagesForReportingOrganization(array $formFields)
    {
        $messages = [];
        foreach ($formFields as $reportingOrganizationIndex => $reportingOrganization) {
            $reportingOrganizationForm = sprintf('reporting_org.%s', $reportingOrganizationIndex);
            $messages                  = array_merge(
                $messages,
                $this->getMessagesForNarrative($reportingOrganization['narrative'], $reportingOrganizationForm)
            );
        }

        return $messages;
    }
}
