<?php namespace App\Core\V201\Requests\Activity;

use Illuminate\Support\Facades\Validator;

/**
 * Class CountryBudgetItem
 * @package App\Core\V201\Requests\Activity
 */
class CountryBudgetItem extends ActivityBaseRequest
{

    function __construct()
    {
        Parent::__construct();
        Validator::extend(
            'total',
            function ($attribute, $value, $parameters, $validator) {
                ($value != 100) ? $check = false : $check = true;

                return $check;
            }
        );
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return $this->getCountryBudgetItemRules($this->get('country_budget_item'));
    }

    /**
     * get the error message
     * @return array
     */
    public function messages()
    {
        return $this->getCountryBudgetItemMessages($this->get('country_budget_item'));
    }

    /**
     * returns rules for country budget item form
     * @param $formFields
     * @return array
     */
    public function getCountryBudgetItemRules(array $formFields)
    {
        $rules = [];
        foreach ($formFields as $countryBudgetItemIndex => $countryBudgetItem) {
            $countryBudgetItemForm                                                = sprintf('country_budget_item.%s', $countryBudgetItemIndex);
            $code                                                                 = $countryBudgetItem['vocabulary'] == 1 ? 'code' : 'code_text';
            $rules[sprintf('%s.budget_item.0.%s', $countryBudgetItemForm, $code)] = 'required';
            $rules[sprintf('%s.vocabulary', $countryBudgetItemForm)]              = 'required';
            $rules                                                                = array_merge(
                $rules,
                $this->getBudgetItemRules($countryBudgetItem['budget_item'], $countryBudgetItemForm, $code)
            );
        }

        return $rules;
    }

    /**
     * returns messages for country budget error messages
     * @param $formFields
     * @return array
     */
    public function getCountryBudgetItemMessages(array $formFields)
    {
        $messages = [];
        foreach ($formFields as $countryBudgetItemIndex => $countryBudgetItem) {
            $countryBudgetItemForm                                                            = sprintf('country_budget_item.%s', $countryBudgetItemIndex);
            $code                                                                             = $countryBudgetItem['vocabulary'] == 1 ? 'code' : 'code_text';
            $messages[sprintf('%s.budget_item.0.%s.required', $countryBudgetItemForm, $code)] = 'Code is Required';
            $messages[sprintf('%s.vocabulary.required', $countryBudgetItemForm)]              = 'Vocabulary is required.';
            $messages                                                                         = array_merge(
                $messages,
                $this->getBudgetItemMessages($countryBudgetItem['budget_item'], $countryBudgetItemForm, $code)
            );
        }

        return $messages;
    }

    /**
     * returns budget item validation rules
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getBudgetItemRules(array $formFields, $formBase, $code)
    {
        $rules = [];
        foreach ($formFields as $budgetItemIndex => $budgetItem) {
            $budgetItemForm                                   = sprintf('%s.budget_item.%s', $formBase, $budgetItemIndex);
            $rules[sprintf('%s.percentage', $budgetItemForm)] = 'numeric|max:100';
            $rules[sprintf('%s.%s', $budgetItemForm, $code)]  = 'required';
            $rules                                            = array_merge(
                $rules,
                $this->getBudgetItemDescriptionRules($budgetItem['description'], $budgetItemForm)
            );
            $rules                                            = array_merge(
                $rules,
                $this->getRulesForPercentage($this->get('country_budget_item'))
            );
        }

        return $rules;
    }

    /**
     * return budget item error message
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getBudgetItemMessages(array $formFields, $formBase, $code)
    {
        $messages = [];
        foreach ($formFields as $budgetItemIndex => $budgetItem) {
            $budgetItemForm                                                    = sprintf('%s.budget_item.%s', $formBase, $budgetItemIndex);
            $messages[sprintf('%s.%s.required', $budgetItemForm, $code)]       = 'Code is required';
            $messages[sprintf('%s.percentage.%s', $budgetItemForm, 'numeric')] = 'Percentage should be numeric';
            $messages[sprintf('%s.percentage.%s', $budgetItemForm, 'max')]     = 'Percentage should less than or equal to :max';
            $messages[sprintf('%s.percentage.sum', $budgetItemForm)]           = 'Total percentage of budget items under the same vocabulary must be equal to 100.';
            $messages[sprintf('%s.percentage.required', $budgetItemForm)]      = 'Percentage is required when there are multiple codes  .';
            $messages[sprintf('%s.percentage.total', $budgetItemForm)]         = 'Percentage should be 100 when there is only one budget item.';
            $messages                                                          = array_merge(
                $messages,
                $this->getBudgetItemDescriptionMessages($budgetItem['description'], $budgetItemForm)
            );
        }

        return $messages;
    }

    /**
     * return budget item description rule
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getBudgetItemDescriptionRules(array $formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $descriptionForm = sprintf('%s.description.%s', $formBase, $descriptionIndex);
            $rules           = $this->getRulesForNarrative($description['narrative'], $descriptionForm);
        }

        return $rules;
    }

    /**
     * return budget item description error message
     * @param $formFields
     * @param $formBase
     * @return array
     */
    public function getBudgetItemDescriptionMessages(array $formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $descriptionForm = sprintf('%s.description.%s', $formBase, $descriptionIndex);
            $messages        = $this->getMessagesForNarrative($description['narrative'], $descriptionForm);
        }

        return $messages;
    }

    /** Returns rules for percentage
     * @param $countryBudget
     * @return array
     */
    protected function getRulesForPercentage($countryBudget)
    {
        $countryBudgetItems      = getVal($countryBudget, [0, 'budget_item'], []);
        $totalPercentage         = 0;
        $isEmpty                 = false;
        $countryBudgetPercentage = 0;
        $rules                   = [];

        if (count($countryBudgetItems) > 1) {
            foreach ($countryBudgetItems as $key => $countryBudgetItem) {
                ($countryBudgetItem['percentage'] != "") ? $countryBudgetPercentage = $countryBudgetItem['percentage'] : $isEmpty = true;
                $totalPercentage = $totalPercentage + $countryBudgetPercentage;
            }

            foreach ($countryBudgetItems as $key => $countryBudgetItem) {
                if ($isEmpty) {
                    $rules["country_budget_item.0.budget_item.$key.percentage"] = 'required';
                } elseif ($totalPercentage != 100) {
                    $rules["country_budget_item.0.budget_item.$key.percentage"] = 'sum';
                }
            }
        } else {
            $rules["country_budget_item.0.budget_item.0.percentage"] = 'total';
        }

        return $rules;
    }
}
