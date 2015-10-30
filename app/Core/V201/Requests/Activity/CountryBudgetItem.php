<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class CountryBudgetItem
 * @package App\Core\V201\Requests\Activity
 */
class CountryBudgetItem extends ActivityBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return $this->getCountryBudgetItemRules($this->request->get('country_budget_item'));
    }

    /**
     * get the error message
     * @return array
     */
    public function messages()
    {
        return $this->getCountryBudgetItemMessages($this->request->get('country_budget_item'));
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
            $countryBudgetItemForm = sprintf('country_budget_item.%s', $countryBudgetItemIndex);
            $rules                 = $this->getBudgetItemRules(
                $countryBudgetItem['budget_item'],
                $countryBudgetItemForm
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
            $countryBudgetItemForm = sprintf('country_budget_item.%s', $countryBudgetItemIndex);
            $messages              = $this->getBudgetItemMessages(
                $countryBudgetItem['budget_item'],
                $countryBudgetItemForm
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
    public function getBudgetItemRules(array $formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $budgetItemIndex => $budgetItem) {
            $budgetItemForm                                   = sprintf(
                '%s.budget_item.%s',
                $formBase,
                $budgetItemIndex
            );
            $rules[sprintf('%s.code', $budgetItemForm)]       = 'required';
            $rules[sprintf('%s.percentage', $budgetItemForm)] = 'required|numeric|max:100';
            $rules                                            = array_merge(
                $rules,
                $this->getBudgetItemDescriptionRules($budgetItem['description'], $budgetItemForm)
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
    public function getBudgetItemMessages(array $formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $budgetItemIndex => $budgetItem) {
            $budgetItemForm                                                     = sprintf(
                '%s.budget_item.%s',
                $formBase,
                $budgetItemIndex
            );
            $messages[sprintf('%s.code.%s', $budgetItemForm, 'required')]       = 'Code is required';
            $messages[sprintf('%s.percentage.%s', $budgetItemForm, 'required')] = 'percentage is required';
            $messages[sprintf('%s.percentage.%s', $budgetItemForm, 'numeric')]  = 'percentage should be numeric';
            $messages[sprintf(
                '%s.percentage.%s',
                $budgetItemForm,
                'max:100'
            )]                                                                  = 'percentage should less tha or equal to 100';
            $messages                                                           = array_merge(
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
}
