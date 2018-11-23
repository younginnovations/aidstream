<?php namespace App\Np\Services\Validation\Rules\V202;


/**
 * Class ActivityRequiredFields
 * @package App\Np\Services\Validation\Rules\V202
 */
class ActivityRequiredFields
{
    /**
     * Contains rules that must be fulfilled to create an activity.
     *
     * @var
     */
    protected $rules = [];

    /**
     * Contains messages for the defined rules.
     *
     * @var
     */
    protected $messages = [];

    /**
     * Returns rules
     *
     * @return array
     */
    public function rules()
    {
        $this->rulesForOrganisation()
             ->rulesForSettings();

        return $this->rules;
    }

    /**
     * Returns messages
     * 
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }

    /**
     * Values that should be present in the organisation to add an activity.
     *
     * @return $this
     */
    protected function rulesForOrganisation()
    {
        $this->rules['organisation.reporting_org']                                     = 'required';
        $this->rules['organisation.reporting_org.*.reporting_organization_identifier'] = 'required';
        $this->rules['organisation.reporting_org.*.reporting_organization_type']       = 'required';
        $this->rules['organisation.reporting_org.*.narrative.*.narrative']             = 'required';
        $this->rules['organisation.country']                                           = 'required';
        $this->rules['organisation.registration_agency']                               = 'required';
        $this->rules['organisation.registration_number']                               = 'required';

        return $this;
    }


    /**
     * Values that should be present in the settings to add an activity.
     *
     * @return $this
     */
    protected function rulesForSettings()
    {
        $this->rules['settings.publishing_type']                         = 'required';
        $this->rules['settings.registry_info']                           = 'required';
        $this->rules['settings.default_field_values']                    = 'required';
        $this->rules['settings.default_field_values.*.default_currency'] = 'required';
        $this->rules['settings.default_field_values.*.default_language'] = 'required';

        return $this;
    }
}