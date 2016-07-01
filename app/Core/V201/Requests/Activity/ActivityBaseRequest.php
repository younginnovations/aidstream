<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;
use App\Models\Activity\Activity;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Helpers\GetCodeName;

/**
 * Class ActivityBaseRequest
 * common validation rules and messages
 * @package App\Core\V201\Requests\Activity
 */
class ActivityBaseRequest extends Request
{

    function __construct()
    {

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

        Validator::extendImplicit(
            'sum',
            function ($attribute, $value, $parameters, $validator) {
                return false;
            }
        );

        Validator::extendImplicit(
            'required_with_language',
            function ($attribute, $value, $parameters, $validator) {
                $language = preg_replace('/([^~]+).narrative/', '$1.language', $attribute);

                return !(Input::get($language) && !Input::get($attribute));
            }
        );

        Validator::extend(
            'exclude_operators',
            function ($attribute, $value, $parameters, $validator) {
                return !preg_match('/[\/\&\|\?|]+/', $value);
            }
        );
    }

    /**
     * returns rules for narrative
     * @param      $formFields
     * @param      $formBase
     * @return array
     */
    public function getRulesForNarrative($formFields, $formBase)
    {
        $rules                                       = [];
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_lang';
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_default_lang';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)][] = 'required_with_language';
        }

        return $rules;
    }

    /**
     * get rules for transaction's sector element
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForTransactionSectorNarrative($formFields, $formBase)
    {
        $rules                                       = [];
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_lang';
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_default_lang';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)][] = 'required_with_language';

            if ($narrative['narrative'] != "") {
                $rules[sprintf('%s.sector_vocabulary', $formBase)] = 'required_with:' . sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex);
                $rules[sprintf('%s.sector_code', $formBase)]       = 'required_with:' . sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex);
            }
        }

        return $rules;
    }

    /**
     * Get messages for transaction's sector element
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForTransactionSectorNarrative($formFields, $formBase)
    {
        $messages                                                 = [];
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = 'Languages should be unique.';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[sprintf(
                '%s.narrative.%s.narrative.required_with_language',
                $formBase,
                $narrativeIndex
            )] = 'Narrative is required with language.';

            $messages[sprintf('%s.sector_vocabulary.required_with', $formBase)] = 'Sector Vocabulary is required with Narrative.';
            $messages[sprintf('%s.sector_code.required_with', $formBase)] = 'Sector Code is required with Narrative.';
        }

        return $messages;
    }

    /**
     * returns rules for narrative
     * @param      $formFields
     * @param      $formBase
     * @return array
     */
    public function getRulesForResultNarrative($formFields, $formBase)
    {
        $rules                                       = [];
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_lang';
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_default_lang';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)][] = 'required';
        }

        return $rules;
    }

    /**
     * returns messages for narrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForNarrative($formFields, $formBase)
    {
        $messages                                                 = [];
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = 'Languages should be unique.';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[sprintf(
                '%s.narrative.%s.narrative.required_with_language',
                $formBase,
                $narrativeIndex
            )] = 'Narrative is required with language.';
        }

        return $messages;
    }

    /**
     * returns rules for narrative if narrative is required
     * @param      $formFields
     * @param      $formBase
     * @return array
     */
    public function getRulesForRequiredNarrative($formFields, $formBase)
    {
        $rules                                       = [];
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_lang';
        $rules[sprintf('%s.narrative', $formBase)][] = 'unique_default_lang';

        foreach ($formFields as $narrativeIndex => $narrative) {
            if (boolval($narrative['language'])) {
                $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)] = 'required_with:' . sprintf(
                        '%s.narrative.%s.language',
                        $formBase,
                        $narrativeIndex
                    );
            } else {
                $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)] = 'required';
            }
        }

        return $rules;
    }

    /**
     * get message for narrative
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForRequiredNarrative($formFields, $formBase)
    {
        $messages                                                 = [];
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = 'Languages should be unique';

        foreach ($formFields as $narrativeIndex => $narrative) {
            if (boolval($narrative['language'])) {
                $messages[sprintf(
                    '%s.narrative.%s.narrative.required_with',
                    $formBase,
                    $narrativeIndex
                )] = 'Narrative is required with language';
            } else {
                $messages[sprintf(
                    '%s.narrative.%s.narrative.required',
                    $formBase,
                    $narrativeIndex
                )] = 'Narrative is required';
            }
        }

        return $messages;
    }

    /**
     * returns rules for period start form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForPeriodStart($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $rules[$formBase . '.period_start.' . $periodStartKey . '.date'] = 'required|date';
        }

        return $rules;
    }

    /**
     * returns messages for period start form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForPeriodStart($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodStartKey => $periodStartVal) {
            $messages[$formBase . '.period_start.' . $periodStartKey . '.date.required'] = 'Period Start is required';
            $messages[$formBase . '.period_end.' . $periodStartKey . '.date.date']       = 'Period Start is not a valid date.';
        }

        return $messages;
    }

    /**
     * returns rules for period end form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForPeriodEnd($formFields, $formBase)
    {
        $rules = [];

        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $rules[$formBase . '.period_end.' . $periodEndKey . '.date'][] = 'required';
            $rules[$formBase . '.period_end.' . $periodEndKey . '.date'][] = 'date';
            $rules[$formBase . '.period_end.' . $periodEndKey . '.date'][] = sprintf(
                'after:%s',
                $formBase . '.period_start.' . $periodEndKey . '.date'
            );
        }

        return $rules;
    }

    /**
     * returns messages for period end form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForPeriodEnd($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $periodEndKey => $periodEndVal) {
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.required'] = 'Period End is required.';
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.date']     = 'Period End is not a valid date.';
            $messages[$formBase . '.period_end.' . $periodEndKey . '.date.after']    = 'Period End must be a date after Period Start';
        }

        return $messages;
    }
}
