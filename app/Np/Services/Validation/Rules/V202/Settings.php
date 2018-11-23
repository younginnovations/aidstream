<?php namespace App\Np\Services\Validation\Rules\V202;

use App\Core\V201\Traits\GetCodes;
use App\Models\Organization\Organization;
use Illuminate\Support\Facades\Validator;

/**
 * Class Settings
 * @package App\Np\Services\Validation\Rules\V202
 */
class Settings
{
    use GetCodes;
    /**
     * @var array
     */
    protected $settingsRules = [];

    /**
     * @var array
     */
    protected $methods = [
        'OrganisationName',
        'Language',
        'OrganisationNameAbbreviation',
        'Country',
        'OrganisationRegistrationAgency',
        'OrganisationRegistrationNumber',
        'OrganisationType',
        'OrganisationIatiIdentifier',
        'PublisherId',
        'ApiKey',
        'DefaultCurrency',
        'DefaultLanguage'
    ];

    /**
     * @var array
     */
    protected $settingsMessages = [];

    public function __construct()
    {
        Validator::extendImplicit(
            'include_operators',
            function ($attribute, $value, $parameters, $validator) {

                return preg_match('/^[a-zA-Z0-9\-_]+$/', $value);
            }
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        foreach ($this->methods() as $method) {
            $methodName = sprintf('rulesFor%s', $method);

            if (method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }

        return $this->settingsRules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        foreach ($this->methods() as $method) {
            $methodName = sprintf('messagesFor%s', $method);

            if (method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }

        return $this->settingsMessages;
    }

    /**
     * @return array
     */
    protected function methods()
    {
        return $this->methods;
    }

    /**
     * @return $this
     */
    protected function rulesForOrganisationName()
    {
        $this->settingsRules['organisationName'] = 'required';

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForOrganisationName()
    {
        $this->settingsMessages['organisationName.required'] = trans('validation.required', ['attribute' => trans('lite/settings.organisation_name')]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForLanguage()
    {
        $this->settingsRules['language'] = 'required';

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForLanguage()
    {
        $this->settingsMessages['language.required'] = trans('validation.required', ['attribute' => trans('lite/settings.language')]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForOrganisationNameAbbreviation()
    {

        $organisationIdentifiers = Organization::select('user_identifier')->where('id', '<>', session('org_id'))->get()->toArray();
        $organisationId          = [];
        foreach ($organisationIdentifiers as $organisationIdentifier) {
            $organisationId[] = getVal($organisationIdentifier, ['user_identifier'], '');
        }

        $this->settingsRules['organisationNameAbbreviation'] = sprintf('required|no_spaces|not_in:%s', implode(",", $organisationId));

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForOrganisationNameAbbreviation()
    {
        $this->settingsMessages['organisationNameAbbreviation.required']  = trans('validation.required', ['attribute' => trans('lite/settings.organisation_name_abbreviation')]);
        $this->settingsMessages['organisationNameAbbreviation.not_in']    = trans('validation.custom_unique', ['attribute' => trans('lite/settings.organisation_name_abbreviation')]);
        $this->settingsMessages['organisationNameAbbreviation.no_spaces'] = trans('validation.spaces_not_allowed');

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForCountry()
    {
        $this->settingsRules['country'] = 'required';

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForCountry()
    {
        $this->settingsMessages['country.required'] = trans('validation.required', ['attribute' => trans('lite/settings.country')]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForOrganisationRegistrationAgency()
    {
        $this->settingsRules['organisationRegistrationAgency'] = 'required';

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForOrganisationRegistrationAgency()
    {
        $this->settingsMessages['organisationRegistrationAgency.required'] = trans('validation.required', ['attribute' => trans('lite/settings.organisation_registration_agency')]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForOrganisationRegistrationNumber()
    {
        $this->settingsRules['organisationRegistrationNumber'] = 'required';

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForOrganisationRegistrationNumber()
    {
        $this->settingsMessages['organisationRegistrationNumber.required'] = trans('validation.required', ['attribute' => trans('lite/settings.organisation_registration_number')]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForOrganisationIatiIdentifier()
    {
        $organisationIdentifiers = Organization::select('reporting_org')->where('id', '<>', session('org_id'))->get()->toArray();
        $organisationId          = [];
        foreach ($organisationIdentifiers as $organisationIdentifier) {
            $organisationId[] = getVal($organisationIdentifier, ['reporting_org', 0, 'reporting_organization_identifier'], '');
        }

        $this->settingsRules['organisationIatiIdentifier'] = sprintf('required|not_in:%s', implode(",", $organisationId));

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForOrganisationIatiIdentifier()
    {
        $this->settingsMessages['organisationIatiIdentifier.required'] = trans('validation.required', ['attribute' => trans('lite/settings.organisation_identifier')]);
        $this->settingsMessages['organisationIatiIdentifier.not_in']   = trans('validation.not_in', ['attribute' => trans('lite/settings.organisation_identifier')]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForOrganisationType()
    {
        $this->settingsRules['organisationType'] = 'required';

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForOrganisationType()
    {
        $this->settingsMessages['organisationType.required'] = trans('validation.required', ['attribute' => trans('lite/settings.organisation_type')]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForDefaultCurrency()
    {
        $this->settingsRules['defaultCurrency'] = 'required';

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForDefaultCurrency()
    {
        $this->settingsMessages['defaultCurrency.required'] = trans('validation.required', ['attribute' => trans('lite/settings.default_currency')]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function rulesForDefaultLanguage()
    {
        $this->settingsRules['defaultLanguage'] = 'required';

        return $this;
    }

    /**
     * @return $this
     */
    protected function messagesForDefaultLanguage()
    {
        $this->settingsMessages['defaultLanguage.required'] = trans('validation.required', ['attribute' => trans('lite/settings.default_language')]);

        return $this;
    }

    /**
     * Rules for publisher id
     */
    protected function rulesForPublisherId()
    {
        $ids          = \App\Models\Settings::select('registry_info')->where('organization_id', '<>', session('org_id'))->get()->toArray();
        $publisherIds = [];
        foreach ($ids as $id) {
            $publisherIds[] = getVal($id, ['registry_info', 0, 'publisher_id']);
        }

        if (request()->get('publisherId')) {
            $this->settingsRules['publisherId'] = sprintf('include_operators|not_in:%s', implode(",", $publisherIds));
        }
    }

    /**
     * Messages for publisher id must be unique
     */
    protected function messagesForPublisherId()
    {
        if (request()->get('publisherId')) {
            $this->settingsMessages['publisherId.not_in']            = trans('validation.unique_validation', ['attribute' => trans('lite/settings.publisher_id')]);
            $this->settingsMessages['publisherId.include_operators'] = trans('validation.alpha_dash', ['attribute' => trans('lite/settings.publisher_id')]);
        }
    }
}
