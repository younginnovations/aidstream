<?php namespace App\Tz\Aidstream\Requests;

use App\Http\Requests\Request;

/**
 * Class TransactionRequests
 * @package App\Tz\Aidstream\Requests
 */
class TransactionRequests extends Request
{

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Transaction Rules  
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForTransactions($this->get('transaction'));
    }

    /**
     * Messages for Transaction
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForTransactions($this->get('transaction'));
    }

    /**
     * @param $formFields
     * @return array
     */
    protected function getRulesForTransactions($formFields)
    {
        $rules = [];
        foreach ($formFields as $key => $transaction) {
            $transactionForm                                                                      = 'transaction.' . $key;
            $rules[sprintf('%s.reference', $transactionForm)]                                     = 'required';
            $rules[sprintf('%s.transaction_date.0.date', $transactionForm)]                       = 'required|date';
            $rules[sprintf('%s.value.0.amount', $transactionForm)]                                = 'required|numeric';
            $rules[sprintf('%s.value.0.currency', $transactionForm)]                              = 'required';
            $rules[sprintf('%s.provider_organization.0.narrative.0.narrative', $transactionForm)] = 'required';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @return array
     */
    protected function getMessagesForTransactions($formFields)
    {
        $messages = [];
        foreach ($formFields as $key => $transaction) {
            $transactionForm                                                                                  = 'transaction.' . $key;
            $messages[sprintf('%s.reference.required', $transactionForm)]                                     = 'Transaction Reference is required';
            $messages[sprintf('%s.transaction_date.0.date.required', $transactionForm)]                       = 'Transaction Date is required';
            $messages[sprintf('%s.transaction_date.0.date.date', $transactionForm)]                           = 'Transaction Date must be date';
            $messages[sprintf('%s.value.0.amount.required', $transactionForm)]                                = 'Amount is required';
            $messages[sprintf('%s.value.0.amount.numeric', $transactionForm)]                                  = 'Amount must be numeric';
            $messages[sprintf('%s.value.0.currency.required', $transactionForm)]                              = 'Currency is required';
            $messages[sprintf('%s.provider_organization.0.narrative.0.narrative.required', $transactionForm)] = 'Receiver Organization is required';
        }

        return $messages;
    }
}