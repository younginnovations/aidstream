<?php namespace App\Core\V201\Requests;

use App\Helpers\GetCodeName;
use App\Http\Requests\Request;
use App\Services\Organization\OrganizationManager;
use Illuminate\Support\Facades\Validator;

class SettingsRequest extends Request
{
    protected $reporting_organization_info;

    public function __construct(OrganizationManager $organizationManager)
    {
        $this->organizationManager = $organizationManager;

        Validator::extendImplicit(
            'unique_lang',
            function ($attribute, $value, $parameters, $validator) {
                $languages = [];
                foreach ($value as $narrative) {
                    $language = $narrative['language'];
                    if (in_array($language, $languages)) {
                        return false;
                    }
                    $languages[] = $language;
                }

                return true;
            }
        );

        Validator::extendImplicit(
            'unique_default_lang',
            function ($attribute, $value, $parameters, $validator) {
                $languages       = [];
                $defaultLanguage = getDefaultLanguage();

                $validator->addReplacer(
                    'unique_default_lang',
                    function ($message, $attribute, $rule, $parameters) use ($validator, $defaultLanguage) {
                        return str_replace(':language', app(GetCodeName::class)->getActivityCodeName('Language', $defaultLanguage), $message);
                    }
                );

                $check = true;
                foreach ($value as $narrative) {
                    $languages[] = $narrative['language'];
                }

                if (count($languages) === count(array_unique($languages))) {
                    if (in_array("", $languages) && in_array($defaultLanguage, $languages)) {
                        $check = false;
                    }
                }

                return $check;
            }
        );
    }

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
        Validator::extendImplicit(
            'diff',
            function ($attribute, $value, $parameters, $validator) {
                return false;
            }
        );

        $req                               = $this;
        $this->reporting_organization_info = $req->get('reporting_organization_info')[0];
        $rules                             = [];
        foreach ($this->reporting_organization_info as $key => $val) {
            $rules["reporting_organization_info.0.$key"]      = 'required';
            $rules["reporting_organization_info.0.narrative"] = "unique_lang|unique_default_lang";
        }
        $rules["reporting_organization_info.0.narrative.0.narrative"] = 'required';
        $rules["default_field_values.0.linked_data_uri"]              = 'url';
        $rules["default_field_values.0.default_currency"]             = 'required';
        $rules["default_field_values.0.default_language"]             = 'required';
        $rules["default_field_values.0.default_hierarchy"]            = 'numeric';

        $rules = array_merge($rules, $this->getRulesForReportingOrganization($this->reporting_organization_info));

        return $rules;
    }

    /**
     * write brief description
     * @param $reportOrg
     * @return array
     */
    protected function getRulesForReportingOrganization($reportOrg)
    {
        $rules = [];

        $result = $this->organizationManager->checkReportingOrganization($reportOrg);

        if (!(is_null($result))) {
            $rules["reporting_organization_info.0.reporting_organization_identifier"] = 'required|diff';
        }

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

        foreach ($this->reporting_organization_info as $key => $val) {
            $messages["reporting_organization_info.0.$key.required"] = sprintf("The %s is required.", str_replace('_', ' ', $key));
        }

        $messages["reporting_organization_info.0.narrative.0.narrative.required"] = 'At least one Organization Name is required.';
        $messages["reporting_organization_info.0.narrative.unique_lang"]          = "Language should be unique";
        $messages["default_field_values.0.linked_data_uri.url"]                   = "Linked data uri is invalid.";
        $messages["default_field_values.0.default_currency.required"]             = 'Default Currency is required';
        $messages["default_field_values.0.default_language.required"]             = 'Default Language is required';
        $messages["default_field_values.0.default_hierarchy.numeric"]             = 'Hierarchy should be numeric';
        $messages                                                                 = array_merge($messages, $this->getMessagesForReportingOrganization($this->reporting_organization_info));

        return $messages;
    }

    /**
     * messages for unique of reporting organization
     * @param $reportOrg
     * @return array
     */
    protected function getMessagesForReportingOrganization($reportOrg)
    {
        $messages = [];
        $result   = $this->organizationManager->checkReportingOrganization($reportOrg);

        if (!(is_null($result))) {
            $messages["reporting_organization_info.0.reporting_organization_identifier.diff"] = 'This reporting organization identifier is being used by ' . $result->name . '. This identifier has to be unique. Please contact us at support@aidstream.org';
        }

        return $messages;
    }

}
