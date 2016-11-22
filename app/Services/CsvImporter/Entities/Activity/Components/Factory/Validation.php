<?php namespace App\Services\CsvImporter\Entities\Activity\Components\Factory;

use Carbon\Carbon;
use Illuminate\Validation\Factory;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Validation
 * @package App\Services\CsvImporter\Entities\Activity\Components\Validators
 */
class Validation extends Factory
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * Rules for the validation.
     * @var array
     */
    protected $rules = [];

    /**
     * Messages for failed validation rules.
     * @var array
     */
    protected $messages = [];

    /**
     * Validation constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct($translator);
        $this->registerValidationRules();
    }

    /**
     * Set the data to be validated.
     * @param $data
     * @return $this
     */
    public function sign($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Append rules and messages for the Validator.
     * @param array $rules
     * @param array $messages
     * @return $this
     */
    public function with(array $rules = [], array $messages = [])
    {
        $this->rules    = $rules;
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get the Validator instance for the data to be validated with the current rules and messages.
     * @return \Illuminate\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if (!$this->data) {
            $this->data = [];
        }

        return $this->make($this->data, $this->rules, $this->messages);
    }

    /**
     * Register required validation rules.
     */
    public function registerValidationRules()
    {
        $this->extend(
            'start_end_date',
            function ($attribute, $dates, $parameters, $validator) {
                if ($dates && is_array($dates)) {
                    $actual_start_date  = getVal($dates, ['actual_start_date', 0, 'date']);
                    $actual_end_date    = getVal($dates, ['actual_end_date', 0, 'date']);
                    $planned_start_date = getVal($dates, ['planned_start_date', 0, 'date']);
                    $planned_end_date   = getVal($dates, ['planned_end_date', 0, 'date']);

                    if (($actual_start_date > $actual_end_date) && ($actual_start_date != "" && $actual_end_date != "")) {
                        return false;
                    } elseif (($planned_start_date > $planned_end_date) && ($planned_start_date != "" && $planned_end_date != "")) {
                        return false;
                    } elseif (($actual_start_date > $planned_end_date) && ($actual_start_date != "" && $planned_end_date != "")
                        && ($actual_end_date == "" && $planned_start_date == "")
                    ) {
                        return false;
                    } elseif (($planned_start_date > $actual_end_date) && ($planned_start_date != "" && $actual_end_date != "")
                        && ($planned_end_date == "" && $actual_start_date == "")
                    ) {
                        return false;
                    }

                    return true;
                }

                return false;
            }
        );

        $this->extend(
            'actual_date',
            function ($attribute, $date, $parameters, $validator) {
                $dateType = (!is_array($date)) ?: getVal($date, [0, 'type']);

                if ($dateType == 2 || $dateType == 4) {
                    $actual_date = (!is_array($date)) ?: getVal($date, [0, 'date']);
                    if ($actual_date > date('Y-m-d')) {
                        return false;
                    }
                }

                return true;
            }
        );

        $this->extend(
            'multiple_activity_date',
            function ($attribute, $dates, $parameters, $validator) {
                if ($dates && is_array($dates)) {
                    foreach ($dates as $activityDate) {
                        if (count($activityDate) > 1) {
                            return false;
                        }
                    }

                    return true;
                }

                return false;
            }
        );

        $this->extend(
            'start_date_required',
            function ($attribute, $dates, $parameters, $validator) {
                if (is_array($dates)) {
                    if (array_key_exists('actual_start_date', $dates) || array_key_exists('planned_start_date', $dates)) {
                        return true;
                    }

                    return false;
                }

                return false;
            }
        );

        $this->extend(
            'sector_percentage_sum',
            function ($attribute, $value, $parameters, $validator) {
                $totalPercentage = [];

                if ($value && is_array($value)) {
                    array_walk($value, function ($element) use (&$totalPercentage) {
                        $sectorVocabulary = (integer) $element['sector_vocabulary'];
                        $sectorPercentage = $element['percentage'];

                        if (array_key_exists($sectorVocabulary, $totalPercentage)) {
                            $totalPercentage[$sectorVocabulary] += $sectorPercentage;
                        } else {
                            $totalPercentage[$sectorVocabulary] = $sectorPercentage;
                        }
                    });

                    foreach ($totalPercentage as $key => $percentage) {
                        if ($percentage != "" && $percentage != 100) {
                            return false;
                        }
                    }

                    return true;
                }

                return false;
            }
        );

        $this->extend(
            'percentage_sum',
            function ($attribute, $values, $parameters, $validator) {
                $totalPercentage = 0;
                if ($values) {
                    foreach ($values as $value) {
                        $percentage = $value['percentage'];
                        $totalPercentage += $percentage;
                    }
                    if (count($values) == 1 && $totalPercentage == 0) {
                        return true;
                    }

                    if ($totalPercentage != 100) {
                        return false;
                    }

                    return true;
                }

                return false;
            }
        );

        $this->extend(
            'recipient_country_region_percentage_sum',
            function ($attribute, $value, $parameters, $validator) {
                if ($value != 100) {
                    return false;
                }

                return true;
            }
        );

        $this->extendImplicit(
            'required_only_one_among',
            function ($attribute, $values, $parameters, $validator) {
                list($identifierIndex, $narrativeIndex) = $parameters;
                $isValid = false;

                if ($values) {
                    foreach ($values as $key => $value) {
                        list($identifier, $narratives) = [getVal($value, [$identifierIndex], ''), getVal($value, [$narrativeIndex], [])];

                        foreach ($narratives as $index => $narrative) {
                            $narrativeValue = getVal($narrative, ['narrative']);

                            if (!$identifier && !$narrativeValue) {
                                return false;
                            } else {
                                $isValid = true;
                            }
                        }
                    }
                }

                return $isValid;
            }
        );

        $this->extend(
            'check_sector',
            function ($attribute, $values, $parameters, $validator) {
                $sectorInActivityLevel = true;
                $status                = true;
                foreach ($values as $value) {
                    if ($value['activitySector'] == '') {
                        $sectorInActivityLevel = false;
                    }

                    if ($value['sector_vocabulary'] == '' && $value['sector_code'] == ''
                        && $value['sector_text'] == '' && $value['sector_category_code'] == ''
                        && $sectorInActivityLevel == false
                    ) {
                        $status = false;
                    } elseif (($value['sector_vocabulary'] != '' || $value['sector_code'] != ''
                            || $value['sector_text'] != '' || $value['sector_category_code'] != '')
                        && $sectorInActivityLevel == true
                    ) {
                        $status = false;
                    }
                }

                return $status;
            }
        );

        $this->extend(
            'check_recipient_region_country',
            function ($attribute, $values, $parameters, $validator) {
                $transactionRecipientCountry = getVal($values, ['recipient_country', 0, 'country_code']);
                $transactionRecipientRegion  = getVal($values, ['recipient_region', 0, 'region_code']);
                $activityRecipientCountry    = getVal($values, ['activityRecipientCountry', 0, 'country_code']);
                $activityRecipientRegion     = getVal($values, ['activityRecipientRegion', 0, 'region_code']);

                if (($activityRecipientCountry == "" && $activityRecipientRegion == "")
                    && ($transactionRecipientRegion != "" || $transactionRecipientCountry != "")
                ) {
                    return true;
                }

                if (($activityRecipientCountry != "" || $activityRecipientRegion != "")
                    && ($transactionRecipientRegion == "" && $transactionRecipientCountry == "")
                ) {
                    return true;
                }

                return false;
            }
        );

        $this->extend(
            'start_before_end_date',
            function ($attribute, $values, $parameters, $validator) {
                if (count($values) > 1) {
                    return true;
                }
                foreach ($values as $value) {
                    $periodStart = strtotime(getVal($value, ['period_start', 0, 'date']));
                    $periodEnd   = strtotime(getVal($value, ['period_end', 0, 'date']));


                    if ($periodStart == false || $periodEnd == false) {
                        return true;
                    }

                    if ($periodStart <= $periodEnd) {
                        return true;
                    }

                    return false;
                }
            }
        );

        $this->extend(
            'diff_one_year',
            function ($attribute, $values, $parameters, $validator) {
                if (count($values) > 1) {
                    return true;
                }

                foreach ($values as $value) {
                    $periodStart       = getVal($value, ['period_start', 0, 'date']);
                    $periodEnd         = getVal($value, ['period_end', 0, 'date']);
                    $isPeriodStartDate = strtotime($periodStart);
                    $isPeriodEndDate   = strtotime($periodEnd);

                    if ($isPeriodStartDate != false && $isPeriodEndDate != false) {
                        $periodStart = Carbon::parse($periodStart);
                        $periodEnd   = Carbon::parse($periodEnd);

                        $diff = $periodStart->diff($periodEnd)->days;

                        if ($diff <= 365) {
                            return true;
                        }

                        return false;
                    }

                    return true;
                }
            }
        );

        $this->extend(
            'funding_implementing_required',
            function ($attribute, $values, $parameters, $validator) {
                $org_role = [];
                foreach ($values as $value) {
                    $org_role[] = getVal($value, ['organization_role']);
                }

                if (array_intersect([1, 4], $org_role)) {
                    return true;
                }

                return false;
            }
        );
        $this->extendImplicit(
            'only_one_among',
            function ($attribute, $values, $parameters, $validator) {
                foreach ($values as $value) {
                    if (($value['organization_identifier_code'] == "") && ($value['type'] == "")
                        && (getVal($value, ['provider_activity_id']) == "") && (getVal($value, ['narrative', 0, 'narrative']) == "")
                    ) {
                        return true;
                    }
                    if (($value['organization_identifier_code'] == "") && (getVal($value, ['narrative', 0, 'narrative']) == "")) {
                        return false;
                    }

                    return true;
                }
            }
        );
    }
}
