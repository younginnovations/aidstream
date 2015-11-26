<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class Transaction
 * @package App\Core\V201\Requests\Activity
 */
class Transaction extends ActivityBaseRequest
{

    public function rules()
    {
        return $this->getTransactionRules($this->request->get('transaction'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->getTransactionMessages($this->request->get('transaction'));
    }

    /**
     * returns rules for sector
     * @param $formFields
     * @return array|mixed
     */
    protected function getTransactionRules($formFields)
    {
        $rules = [];

        foreach ($formFields as $transactionIndex => $transaction) {
            $transactionForm                                  = sprintf('transaction.%s', $transactionIndex);
            $rules[sprintf('%s.reference', $transactionForm)] = 'required';
            $rules                                            = array_merge(
                $rules,
                $this->getTransactionTypeRules($transaction['transaction_type'], $transactionForm),
                $this->getTransactionDateRules($transaction['transaction_date'], $transactionForm),
                $this->getValueRules($transaction['value'], $transactionForm),
                $this->getDescriptionRules($transaction['description'], $transactionForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for sector
     * @param $formFields
     * @return array|mixed
     */
    protected function getTransactionMessages($formFields)
    {
        $messages = [];

        foreach ($formFields as $transactionIndex => $transaction) {
            $transactionForm                                              = sprintf('transaction.%s', $transactionIndex);
            $messages[sprintf('%s.reference.required', $transactionForm)] = 'Reference is required';
            $messages                                                     = array_merge(
                $messages,
                $this->getTransactionTypeMessages($transaction['transaction_type'], $transactionForm),
                $this->getTransactionDateMessages($transaction['transaction_date'], $transactionForm),
                $this->getValueMessages($transaction['value'], $transactionForm),
                $this->getDescriptionMessages($transaction['description'], $transactionForm)
            );
        }

        return $messages;
    }

    /**
     * get transaction type rules
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getTransactionTypeRules($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $typeIndex => $type) {
            $typeForm                                              = sprintf('%s.transaction_type.%s', $formBase, $typeIndex);
            $rules[sprintf('%s.transaction_type_code', $typeForm)] = 'required';
        }

        return $rules;
    }

    /**
     * get transaction type error message
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getTransactionTypeMessages($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $typeIndex => $type) {
            $typeForm                                                          = sprintf('%s.transaction_type.%s', $formBase, $typeIndex);
            $messages[sprintf('%s.transaction_type_code.required', $typeForm)] = 'Transaction type is required';
        }

        return $messages;
    }

    /**
     * get transaction date rules
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getTransactionDateRules($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $dateIndex => $date) {
            $dateForm                             = sprintf('%s.transaction_date.%s', $formBase, $dateIndex);
            $rules[sprintf('%s.date', $dateForm)] = 'required';
        }

        return $rules;
    }

    /**
     * get transaction date error message
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getTransactionDateMessages($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $dateIndex => $date) {
            $dateForm                                         = sprintf('%s.transaction_date.%s', $formBase, $dateIndex);
            $messages[sprintf('%s.date.required', $dateForm)] = 'Date is required';
        }

        return $messages;
    }

    /**
     * get values rules
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getValueRules($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $valueIndex => $value) {
            $valueForm                               = sprintf('%s.value.%s', $formBase, $valueIndex);
            $rules[sprintf('%s.amount', $valueForm)] = 'required|numeric';
            $rules[sprintf('%s.date', $valueForm)]   = 'required';
        }

        return $rules;
    }

    /**
     * get value error message
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getValueMessages($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $valueIndex => $value) {
            $valueForm                                           = sprintf('%s.value.%s', $formBase, $valueIndex);
            $messages[sprintf('%s.amount.required', $valueForm)] = 'Amount is required';
            $messages[sprintf('%s.amount.numeric', $valueForm)]  = 'Amount must be numeric';
            $messages[sprintf('%s.date.required', $valueForm)]   = 'Date is required';
        }

        return $messages;
    }

    /**
     * get description rules
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getDescriptionRules($formFields, $formBase)
    {
        $rules = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.description.%s', $formBase, $descriptionIndex);
            $rules         = array_merge(
                $rules,
                $this->getRulesForNarrative($description['narrative'], $narrativeForm)
            );
        }

        return $rules;
    }

    /**
     * get description error message
     * @param $formFields
     * @param $formBase
     * @return array
     */
    protected function getDescriptionMessages($formFields, $formBase)
    {
        $messages = [];
        foreach ($formFields as $descriptionIndex => $description) {
            $narrativeForm = sprintf('%s.description.%s', $formBase, $descriptionIndex);
            $messages      = array_merge(
                $messages,
                $this->getMessagesForNarrative($description['narrative'], $narrativeForm)
            );
        }

        return $messages;
    }
}
