<?php namespace App\Core\V201\Requests\Organization;

use App\Models\Organization;

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
        return $this->addRulesForReportingOrganization($this->request->get('reporting_org'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addRulesForReportingOrganization(array $formFields)
    {
        $rules = [];
        foreach ($formFields as $reportingOrganizationIndex => $reportingOrganization) {
            $reportingOrganizationForm = sprintf('reporting_org.%s', $reportingOrganizationIndex);
            $rules                     = array_merge(
                $rules,
                $this->addRulesForNarrative($reportingOrganization['narrative'], $reportingOrganizationForm)
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
        return $this->addMessagesForReportingOrganization($this->request->get('reporting_org'));
    }

    public function addMessagesForReportingOrganization(array $formFields)
    {
        $messages = [];
        foreach ($formFields as $reportingOrganizationIndex => $reportingOrganization) {
            $reportingOrganizationForm = sprintf('reporting_org.%s', $reportingOrganizationIndex);
            $messages                  = array_merge(
                $messages,
                $this->addMessagesForNarrative($reportingOrganization['narrative'], $reportingOrganizationForm)
            );
        }

        return $messages;
    }
}
