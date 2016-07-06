<?php namespace App\Core\V201\Parser;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Core\V201\Traits\GetCodes;

/**
 * Class ActivityCsvFieldChecker
 * @package App\Core\V201\Parser
 */
class ActivityCsvFieldChecker
{
    use GetCodes;

    /**
     * @var array
     */
    protected $csvRow = [];
    /**
     * @var array
     */
    protected $rules = [];
    /**
     * @var array
     */
    protected $messages = [];

    public function __construct()
    {
        Validator::extendImplicit(
            'required_any',
            function ($attribute, $value, $parameters, $validator) {
                for ($i = 1; $i < count($parameters); $i = $i + 2) {
                    $value = $parameters[$i];
                    if (!empty($value)) {
                        return true;
                    }
                }

                return false;
            }
        );

        Validator::extend(
            'code_list',
            function ($attribute, $value, $parameters, $validator) {
                $listName = $parameters[1];
                $listType = $parameters[0];
                $codeList = $this->getCodes($listName, $listType);

                return !array_diff(explode(';', $value), $codeList);
            }
        );

        Validator::extend(
            'unique_activity_identifier',
            function ($attribute, $value, $parameters, $validator) {
                $table    = 'activity_data';
                $column   = 'identifier';
                $jsonPath = '{activity_identifier}';
                $orgId    = session('org_id');
                $builder  = DB::table($table)->whereRaw(sprintf("%s #>> '{%s}' = ?", $column, str_replace('.', ',', $jsonPath)), [$value])->where('organization_id', $orgId);
                $count    = $builder->count();

                $validator->addReplacer(
                    'unique_activity_identifier',
                    function ($message, $attribute, $rule, $parameters) use ($validator, $builder) {
                        $activity = $builder->first();
                        if ($activity) {
                            $activityId    = $activity->id;
                            $identifier = json_decode($activity->identifier, true);
                            $activityTitle = $activity->title ? json_decode($activity->title, true)[0]['narrative'] : $identifier['iati_identifier_text'];
                            $message       = str_replace(':activity_identifier', $identifier['activity_identifier'], $message);
                            $message       = str_replace(':activity_id', $activityId, $message);
                            $message       = str_replace(':activity_title', $activityTitle, $message);
                        }

                        return $message;
                    }
                );

                return $count === 0;
            }
        );

        Validator::extend(
            'is_single_value',
            function ($attribute, $value, $parameters, $validator) {
                return (count(explode(';', $value)) == 1);
            }
        );
    }

    /**
     * set imported csv row and return class
     * @param $csvRow
     * @return ActivityCsvFieldChecker
     */
    public function init($csvRow)
    {
        $this->csvRow = $csvRow;

        return $this;
    }

    /**
     * add rules and messages for Identifier
     */
    public function checkIdentifier()
    {
        $this->rules['activity_identifier']                               = 'required|unique_activity_identifier';
        $this->messages['activity_identifier.required']                   = 'Activity Identifier is required.';
        $this->messages['activity_identifier.unique_activity_identifier'] = sprintf(
            'The activity identifier "%s" is already in use.<a href="%s" target="_blank">See %s</a>.',
            ':activity_identifier',
            route('activity.show', ':activity_id'),
            ':activity_title'
        );
    }

    /**
     * add rules and messages for Title
     */
    public function checkTitle()
    {
        $this->rules['activity_title']             = 'required';
        $this->messages['activity_title.required'] = 'Activity Title is required.';
    }

    /**
     * add rules and messages for Descriptions
     */
    public function checkDescription()
    {
        $this->rules['description_general']                 = sprintf(
            'required_any:description_general,%s,description_objectives,%s,description_target_group,%s,description_other,%s',
            $this->csvRow['description_general'],
            $this->csvRow['description_objectives'],
            $this->csvRow['description_target_group'],
            $this->csvRow['description_other']
        );
        $this->messages['description_general.required_any'] = 'At least one of Description General/Objectives/Target Group/Other is required.';
    }

    /**
     * add rules and messages for Status
     */
    public function checkStatus()
    {
        $this->rules['activity_status']              = 'required|code_list:Activity,ActivityStatus';
        $this->messages['activity_status.required']  = 'Activity Status is required.';
        $this->messages['activity_status.code_list'] = 'Activity Status is not valid.';
    }

    /**
     * add rules and messages for Dates
     */
    public function checkDate()
    {
        $this->rules['actual_start_date']                 = sprintf(
            'date|required_any:actual_start_date,%s,planned_start_date,%s',
            $this->csvRow['actual_start_date'],
            $this->csvRow['planned_start_date']
        );
        $this->rules['actual_end_date']                   = 'date|after:actual_start_date';
        $this->rules['planned_start_date']                = 'date';
        $this->rules['planned_end_date']                  = 'date|after:planned_start_date';
        $this->messages['actual_start_date.date']         = 'Actual Start Date is invalid.';
        $this->messages['actual_start_date.required_any'] = 'At least on of Actual/Planned Start Date is required.';
        $this->messages['actual_end_date.date']           = 'Actual End Date is invalid.';
        $this->messages['actual_end_date.after']          = 'Actual End Date should be after Actual Start Date.';
        $this->messages['planned_start_date.date']        = 'Planned Start Date is invalid.';
        $this->messages['planned_end_date.date']          = 'Planned End Date is invalid.';
        $this->messages['planned_end_date.after']         = 'Planned End Date should after Planned Start Date.';
    }

    /**
     * add rules and messages for Participating Organizations
     */
    public function checkParticipatingOrg()
    {
        $this->rules['funding_participating_organization']                 = sprintf(
            'required_any:funding_participating_organization,%s,implementing_participating_organization,%s',
            $this->csvRow['funding_participating_organization'],
            $this->csvRow['implementing_participating_organization']
        );
        $this->messages['funding_participating_organization.required_any'] = 'At least one of Funding/Implementing Participating Organization is required.';
    }

    /**
     * add rules and messages for Recipient Country/Region
     */
    public function checkRecipientCountryOrRegion()
    {
        $this->rules['recipient_country']                 = sprintf(
            'code_list:Organization,Country|required_any:recipient_country,%s,recipient_region,%s',
            $this->csvRow['recipient_country'],
            $this->csvRow['recipient_region']
        );
        $this->rules['recipient_region']                  = 'code_list:Activity,Region';
        $this->messages['recipient_country.required_any'] = 'At least one of Recipient Country/Region is required.';
        $this->messages['recipient_country.code_list']    = 'Recipient Country is invalid.';
        $this->messages['recipient_region.code_list']     = 'Recipient Region is invalid.';
    }

    /**
     * add rules and messages for Sector
     */
    public function checkSector()
    {
        $this->rules['sector_dac_5digit']              = 'required|code_list:Activity,Sector';
        $this->messages['sector_dac_5digit.required']  = 'Sector DAC 5 digit code is Required.';
        $this->messages['sector_dac_5digit.code_list'] = 'Sector DAC 5 digit code is not valid.';
    }

    /**
     * add rules and messages for Scope
     */
    public function checkScope()
    {
        $this->rules['activity_scope']              = 'required|code_list:Activity,ActivityScope';
        $this->messages['activity_scope.required']  = 'Activity scope is Required.';
        $this->messages['activity_scope.code_list'] = 'Activity scope is not valid.';
    }

    /**
     * return validation messages
     * @return array/string
     */
    public function getErrors()
    {
        $validator = Validator::make($this->csvRow, $this->rules, $this->messages);
        $this->setSingleValueRules($validator);

        return $this->parseErrors($validator);
    }

    /**
     * return parsed validation messages
     * @param $validator
     * @return array/string
     */
    protected function parseErrors($validator)
    {
        $validatorErrors = $validator->errors()->getMessages();
        if (isset($validator->failed()['activity_identifier']['UniqueActivityIdentifier'])) {
            return ['duplicate' => $validatorErrors['activity_identifier'][0]];
        }
        $errors = [];
        foreach ($validatorErrors as $error) {
            $errors = array_merge($errors, $error);
        }

        return $errors;
    }

    /**
     * set single value validation to appropriate fields
     * @param $validator
     */
    protected function setSingleValueRules(&$validator)
    {
        $singleValues = [
            'activity_identifier'      => 'Activity Identifier',
            'activity_title'           => 'Activity Title',
            'activity_status'          => 'Activity Status',
            'actual_start_date'        => 'Actual Start Date',
            'actual_end_date'          => 'Actual End Date',
            'planned_start_date'       => 'Planned Start Date',
            'planned_end_date'         => 'Planned End Date',
            'activity_scope'           => 'Activity Scope'
        ];

        $messages = [];
        foreach ($singleValues as $key => $label) {
            $validator->mergeRules($key, 'is_single_value');
            $messages[sprintf('%s.is_single_value', $key)] = sprintf('%s doesn\'t support multiple values', $label);
        }
        $validator->setCustomMessages($messages);
    }
}
