<?php namespace App\Np\Services\Validation\Rules\V202;

use App\Core\V201\Traits\GetCodes;
use App\Models\Activity\Activity as ActivityModel;

/**
 * Class Activity
 * @package App\Np\Services\Validation\Rules\V202
 */
class Activity
{
    use GetCodes;

    /**
     * Contains rules for the Activity.
     *
     * @var array
     */
    protected $activityRules = [];

    /**
     * List of the methods to set rules and messages.
     *
     * @var array
     */
    protected $methods = [
        'ActivityIdentifier',
        'ActivityTitle',
        'GeneralDescription',
        'ActivityStatus',
        'Sector',
        'StartDate',
        'EndDate',
        'OrganisationName',
        'OrganisationType',
        'DocumentUrl',
        'Location'
    ];

    /**
     * Contains messages for the Activity.
     *
     * @var array
     */
    protected $activityMessages = [];

    /**
     * Calls and sets $this->activityRules from $this->methods.
     *
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

        return $this->activityRules;
    }

    /**
     * Calls and sets $this->activityMessages from $this->methods.
     *
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

        return $this->activityMessages;
    }

    /**
     * Returns available methods.
     *
     * @return array
     */
    protected function methods()
    {
        return $this->methods;
    }

    /**
     * Sets rules for the activity identifier.
     */
    protected function rulesForActivityIdentifier()
    {
        $this->activityRules['activity_identifier'] = sprintf('required|not_in:%s', $this->getActivityIdentifiers());
    }

    /**
     * Sets messages for the activity identifier.
     */
    protected function messagesForActivityIdentifier()
    {
        $this->activityMessages['activity_identifier.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.activity_identifier')]);
        $this->activityMessages['activity_identifier.not_in']   = trans('validation.not_in', ['attribute' => trans('lite/elementForm.activity_identifier')]);
    }

    /**
     * Sets rules for the activity title.
     */
    protected function rulesForActivityTitle()
    {
        $this->activityRules['activity_title'] = 'required';
    }

    /**
     * Sets messages for the activity title.
     */
    protected function messagesForActivityTitle()
    {
        $this->activityMessages['activity_title.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.activity_title')]);
    }

    /**
     * Sets rules for general description.
     */
    protected function rulesForGeneralDescription()
    {
        $this->activityRules['general_description'] = 'required';
    }

    /**
     * Sets messages for general description.
     */
    protected function messagesForGeneralDescription()
    {
        $this->activityMessages['general_description.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.general_description')]);
    }

    /**
     * Sets rules for activity status.
     */
    protected function rulesForActivityStatus()
    {
        $this->activityRules['activity_status'] = sprintf('required|in:%s', $this->getStringFormatCode('ActivityStatus', 'Activity'));
    }

    /**
     * Sets messages for activity status.
     */
    protected function messagesForActivityStatus()
    {
        $this->activityMessages['activity_status.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.general_description')]);
        $this->activityMessages['activity_status.in']       = trans('validation.code_list', ['attribute' => trans('lite/elementForm.general_description')]);
    }

    /**
     * Sets rules for sector.
     */
    protected function rulesForSector()
    {
        $this->activityRules['sector.*'] = sprintf('required|in:%s', $this->getStringFormatCode('Sector', 'Activity'));
    }

    /**
     * Sets messages for sector.
     */
    protected function messagesForSector()
    {
        $this->activityMessages['sector.*.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.sector')]);
        $this->activityMessages['sector.*.in']       = trans('validation.code_list', ['attribute' => trans('lite/elementForm.sector')]);
    }

    /**
     * Sets rules for start date.
     */
    protected function rulesForStartDate()
    {
        $this->activityRules['start_date'] = 'required|date';
    }

    /**
     * Sets messages for start date.
     */
    protected function messagesForStartDate()
    {
        $this->activityMessages['start_date.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.start_date')]);
        $this->activityMessages['start_date.date']     = trans('validation.date', ['attribute' => trans('lite/elementForm.start_date')]);
    }

    /**
     * Sets rules for end date.
     */
    protected function rulesForEndDate()
    {
        $this->activityRules['end_date'] = 'date|after:start_date';
    }

    /**
     * Sets messages for end date.
     */
    protected function messagesForEndDate()
    {
        $this->activityMessages['end_date.date']  = trans('validation.date', ['attribute' => trans('lite/elementForm.end_date')]);
        $this->activityMessages['end_date.after'] = trans('validation.after', ['attribute' => trans('lite/elementForm.end_date'), 'date' => trans('lite/elementForm.start_date')]);
    }

    /**
     * Sets rules for country.
     */
    protected function rulesForCountry()
    {
        $this->activityRules['country'] = sprintf('required|in:%s', $this->getStringFormatCode('Country', 'Organization'));
    }

    /**
     * Sets messages for country.
     */
    protected function messagesForCountry()
    {
        $this->activityMessages['country.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.country')]);
        $this->activityMessages['country.in']       = trans('validation.code_list', ['attribute' => trans('lite/elementForm.country')]);
    }

    // protected function rulesForMunicipality()
    // {
    //     $this->activityRules['municipality'] = 'required';
    // }
    // protected function messagesForMunicipality()
    // {
    //     $this->activityMessages['municipality.*.required'] = trans('validation.required', ['attribute' => 'municipality is required']);
    // }
    /**
     * Sets rules for organisation name.
     */
    protected function rulesForOrganisationName()
    {
        $this->activityRules['implementing_organisations.*.organisation_name'] = 'required';
    }

    /**
     * Sets messages for organisation name.
     */
    protected function messagesForOrganisationName()
    {
        $this->activityMessages['implementing_organisations.*.organisation_name.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.implementing_organisation_name')]);
    }

    /**
     * Sets rules for organisation type.
     */
    protected function rulesForOrganisationType()
    {
        $this->activityRules['funding_organisations.*.organisation_type']      = sprintf('in:%s', $this->getStringFormatCode('OrganizationType', 'Organization'));
        $this->activityRules['implementing_organisations.*.organisation_type'] = sprintf('required|in:%s', $this->getStringFormatCode('OrganizationType', 'Organization'));
    }

    /**
     * Sets messages for organisation type.
     */
    protected function messagesForOrganisationType()
    {
        $this->activityMessages['funding_organisations.*.organisation_type.in']            = trans('validation.code_list', ['attribute' => trans('lite/elementForm.funding_organisation_type')]);
        $this->activityMessages['implementing_organisations.*.organisation_type.required'] = trans('validation.required', ['attribute' => trans('lite/elementForm.implementing_organisation_type')]);
        $this->activityMessages['implementing_organisations.*.organisation_type.in']       = trans('validation.code_list', ['attribute' => trans('lite/elementForm.implementing_organisation_type')]);
    }

    protected function rulesForDocumentUrl()
    {
        $this->activityRules['outcomes_document.0.document_url'] = 'required_with:outcomes_document.0.document_title|url';
        $this->activityRules['annual_report.0.document_url']     = 'required_with:annual_report.0.document_title|url';
    }

    protected function messagesForDocumentUrl()
    {
        $this->activityMessages['outcomes_document.0.document_url.required_with'] = trans('validation.required_with', ['attribute' => trans('lite/elementForm.outcomes_document_url'), 'values' => trans('lite/elementForm.outcomes_document_title')]);
        $this->activityMessages['annual_report.0.document_url.required_with']     = trans('validation.required_with', ['attribute' => trans('lite/elementForm.annual_report_url'), 'values' => trans('lite/elementForm.annual_report_title')]);
        $this->activityMessages['outcomes_document.0.document_url.url']           = trans('validation.url');
        $this->activityMessages['annual_report.0.document_url.url']               = trans('validation.url');
    }

    protected function rulesForLocation()
    {
        $this->activityRules['location.*.administrative.*.point.latitude']  = 'numeric';
        $this->activityRules['location.*.administrative.*.point.longitude'] = 'numeric';
        // $this->activityRules['location.*.country']                          = sprintf('required|in:%s', $this->getStringFormatCode('Country', 'Organization'));
    }

    protected function messagesForLocation()
    {
        // $this->activityMessages['location.*.country.required']                        = trans('validation.required', ['attribute' => trans('lite/elementForm.country')]);
        // $this->activityMessages['location.*.country.in']                              = trans('validation.code_list', ['attribute' => trans('lite/elementForm.country')]);
        $this->activityMessages['location.*.administrative.*.point.latitude.numeric'] = trans('validation.numeric', ['attribute' => trans('lite/elementForm.latitude')]);
        $this->activityMessages['location.*.administrative.*.point.longitude']        = trans('validation.numeric', ['attribute' => trans('lite/elementForm.longitude')]);
    }

    /**
     *  Return list of activity Identifiers of the organisation.
     *  If activityId is present in url then query is further filtered by the activity id.
     *
     * @return string
     */
    protected function getActivityIdentifiers()
    {
        if ($activityId = request()->route()->activity) {
            $activities = ActivityModel::where('organization_id', session('org_id'))->where('id', '<>', $activityId)->get();
        } else {
            $activities = ActivityModel::where('organization_id', session('org_id'))->get();
        }
        $activityIdentifiers = [];

        foreach ($activities as $activity) {
            $activityIdentifiers[] = getVal($activity->identifier, ['activity_identifier']);
        }

        return implode(",", $activityIdentifiers);
    }
}
