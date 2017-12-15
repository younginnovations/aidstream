<?php namespace App\Services\Xml\Validator\Traits;

use App\Helpers\GetCodeName;
use Illuminate\Support\Facades\Input;

/**
 * Class RegistersValidationRules
 * @package App\Services\Xml\Validator\Traits
 */
trait RegistersValidationRules
{
    /**
     * Register the required validation rules.
     */
    protected function registerValidationRules()
    {
        $this->extendImplicit(
            'unique_lang',
            function ($attribute, $value, $parameters, $validator) {
                $languages = [];
                foreach ((array) $value as $narrative) {
                    $language = $narrative['language'];
                    if (in_array($language, $languages)) {
                        return false;
                    }
                    $languages[] = $language;
                }

                return true;
            }
        );

        $this->extendImplicit(
            'unique_default_lang',
            function ($attribute, $value, $parameters, $validator) {
                $languages       = [];
                $defaultLanguage = getDefaultLanguage();

                $validator->addReplacer(
                    'unique_default_lang',
                    function ($message, $attribute, $rule, $parameters) use ($validator, $defaultLanguage) {
//                        return str_replace(':language', app(GetCodeName::class)->getActivityCodeName('Language', $defaultLanguage), $message);
                        return str_replace(':language', app(GetCodeName::class)->getActivityCodeName('Language', $defaultLanguage), $message);
                    }
                );

                $check = true;
                foreach ((array) $value as $narrative) {
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

        $this->extendImplicit(
            'sum',
            function ($attribute, $value, $parameters, $validator) {
                return false;
            }
        );

        $this->extendImplicit('total', function ($attribute, $value, $parameters, $validator) {
            if ($value == 100) {
                return true;
            }

            return false;
        });

        $this->extendImplicit(
            'required_with_language',
            function ($attribute, $value, $parameters, $validator) {
                $language = preg_replace('/([^~]+).narrative/', '$1.language', $attribute);

                return !(Input::get($language) && !Input::get($attribute));
            }
        );

        $this->extend(
            'exclude_operators',
            function ($attribute, $value, $parameters, $validator) {
                return !preg_match('/[\&\|\?|]+/', $value);
            }
        );

        $this->extend(
            'start_end_date',
            function ($attribute, $dates, $parameters, $validator) {
                $actual_start_date  = '';
                $actual_end_date    = '';
                $planned_start_date = '';
                $planned_end_date   = '';

                foreach ($dates as $date) {
                    $actual_start_date  = (getVal($date, ['type']) == 2) ? getVal($date, ['date']) : $actual_start_date;
                    $actual_end_date    = (getVal($date, ['type']) == 4) ? getVal($date, ['date']) : $actual_end_date;
                    $planned_start_date = (getVal($date, ['type']) == 1) ? getVal($date, ['date']) : $planned_start_date;
                    $planned_end_date   = (getVal($date, ['type']) == 3) ? getVal($date, ['date']) : $planned_end_date;
                }

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
            'start_date_required',
            function ($attribute, $dates, $parameters, $validator) {
                $dateTypes = [];
                foreach ($dates as $date) {
                    $dateTypes[] = $date['type'];
                }
                if (array_key_exists('1', array_flip($dateTypes))
                    || array_key_exists('2', array_flip($dateTypes))
                ) {
                    return true;
                }

                return false;
            }
        );

        $this->extendImplicit(
            'year_value_narrative_validation',
            function ($attribute, $value, $parameters, $validator) {
                $narratives   = $value['comment'][0]['narrative'];
                $hasNarrative = false;
                foreach ($narratives as $narrative) {
                    if ($narrative['narrative']) {
                        $hasNarrative = true;
                        break;
                    }
                }

                if (!$hasNarrative) {
                    return true;
                }

                isset($value['year']) ?: $value['year'] = null;
                isset($value['value']) ?: $value['value'] = null;

                return ($hasNarrative && ($value['year'] || $value['value']));
            }
        );
    }
}
