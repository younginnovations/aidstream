<?php namespace App\Core\V201\Requests\Organization;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

/**
 * Class OrganizationBaseRequest
 * common validation rules and messages
 * @package App\Core\V201\Requests\Organization
 */
class OrganizationBaseRequest extends Request
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
            'required_with_language',
            function ($attribute, $value, $parameters, $validator) {
                $language = preg_replace('/([^~]+).narrative/', '$1.language', $attribute);

                return !(Input::get($language) && !Input::get($attribute));
            }
        );
    }

    /**
     * returns rules for narrative form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForNarrative($formFields, $formBase)
    {
        $rules                                     = [];
        $rules[sprintf('%s.narrative', $formBase)] = 'unique_lang';

        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)][] = 'required_with_language';
        }

        return $rules;
    }

    /**
     * returns messages for narrative form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForNarrative($formFields, $formBase)
    {
        $messages                                                 = [];
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = 'Languages should be unique.';

        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[sprintf('%s.narrative.%s.narrative.required_with_language', $formBase, $narrativeIndex)] = 'Narrative is required with language.';
        }

        return $messages;
    }

    /**
     * returns rules for value form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForValue($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $valueKey => $valueVal) {
            $valueForm                         = $formBase . '.value.' . $valueKey;
            $rules[$valueForm . '.amount']     = sprintf('required|numeric');
            $rules[$valueForm . '.value_date'] = sprintf('required|date');
        }

        return $rules;
    }

    /**
     * returns messages for value form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesForValue($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $valueKey => $valueVal) {
            $valueForm                                     = $formBase . '.value.' . $valueKey;
            $messages[$valueForm . '.amount.required']     = 'Amount is Required';
            $messages[$valueForm . '.amount.numeric']      = 'Amount should be numeric.';
            $messages[$valueForm . '.value_date.required'] = 'Value date is required.';
            $messages[$valueForm . '.value_date.date']     = 'Please enter valid  date.';
        }

        return $messages;
    }

    /**
     * returns rules for budget line form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForBudgetLine($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $budgetLineKey => $budgetLineVal) {
            $budgetLineForm = $formBase . '.budget_line.' . $budgetLineKey;
            $rules          = array_merge(
                $rules,
                $this->getRulesForBudgetOrExpenseLineValue($budgetLineVal['value'], $budgetLineForm),
                $this->getRulesForBudgetOrExpenseLineNarrative($budgetLineVal['narrative'], $budgetLineForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for budget line form
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getMessagesBudgetLine($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $budgetLineKey => $budgetLineVal) {
            $budgetLineForm = $formBase . '.budget_line.' . $budgetLineKey;
            $messages       = array_merge(
                $messages,
                $this->getMessagesForBudgetOrExpenseLineValue($budgetLineVal['value'], $budgetLineForm),
                $this->getMessagesForBudgetOrExpenseLineNarrative($budgetLineVal['narrative'], $budgetLineForm)
            );
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
            $rules[$formBase . '.period_end.' . $periodEndKey . '.date'] = sprintf('required|date|after:%s', $formBase . '.period_start.' . $periodEndKey . '.date');
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

    /** returns rules for budget line value or expense line value.
     * @param $formField
     * @param $formBase
     * @return mixed
     */
    public function getRulesForBudgetOrExpenseLineValue($formField, $formBase)
    {
        foreach ($formField as $budgetLineIndex => $budgetLine) {
            $rules[$formBase . '.value.' . $budgetLineIndex . '.amount']     = sprintf(
                'required_with:%s,%s,%s|numeric',
                $formBase . '.value.' . $budgetLineIndex . '.value_date',
                $formBase . '.reference',
                $formBase . '.narrative.0.narrative'
            );
            $rules[$formBase . '.value.' . $budgetLineIndex . '.value_date'] = sprintf(
                'required_with:%s,%s,%s|date',
                $formBase . '.value.' . $budgetLineIndex . '.amount',
                $formBase . '.reference',
                $formBase . '.narrative.0.narrative'
            );
        }

        return $rules;
    }


    /** returns messages for budget line value or expense line value .
     * @param        $formField
     * @param        $formBase
     * @param string $type
     * @return mixed
     */
    public function getMessagesForBudgetOrExpenseLineValue($formField, $formBase, $type = 'Budget line')
    {
        foreach ($formField as $budgetLineIndex => $budgetLine) {
            $messages[$formBase . '.value.' . $budgetLineIndex . '.amount' . '.required_with']     = sprintf('Amount is required in %s.', $type);
            $messages[$formBase . '.value.' . $budgetLineIndex . '.amount' . '.numeric']           = 'Amount should be number';
            $messages[$formBase . '.value.' . $budgetLineIndex . '.value_date' . '.date']          = 'Please enter a valid date';
            $messages[$formBase . '.value.' . $budgetLineIndex . '.value_date' . '.required_with'] = sprintf('Value date is required in %s.', $type);
        }

        return $messages;
    }

    /** returns rules for narrative form.
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getRulesForBudgetOrExpenseLineNarrative($formFields, $formBase)
    {
        $rules                                     = [];
        $rules[sprintf('%s.narrative', $formBase)] = 'unique_lang';
        foreach ($formFields as $narrativeIndex => $narrative) {
            $rules[sprintf('%s.narrative.%s.narrative', $formBase, $narrativeIndex)] = sprintf(
                'required_with:%s,%s,%s',
                $formBase . '.value.0' . '.amount',
                $formBase . '.value.0' . '.value_date',
                $formBase . '.reference'
            );
        }

        return $rules;
    }

    /**
     * returns messages for narrative form
     * @param        $formFields
     * @param        $formBase
     * @param string $type
     * @return array
     */
    public function getMessagesForBudgetOrExpenseLineNarrative($formFields, $formBase, $type = "Budget line")
    {
        $messages                                                 = [];
        $messages[sprintf('%s.narrative.unique_lang', $formBase)] = 'Languages should be unique.';

        foreach ($formFields as $narrativeIndex => $narrative) {
            $messages[sprintf('%s.narrative.%s.narrative.required_with', $formBase, $narrativeIndex)] = sprintf('Narrative is required in %s', $type);
        }

        return $messages;
    }
}
