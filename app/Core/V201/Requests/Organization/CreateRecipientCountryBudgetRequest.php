<?php namespace App\Core\V201\Requests\Organization;

use App\Http\Requests\Request;
use App\Models\OrganizationData;
use Illuminate\Foundation\Http\FormRequest;

class CreateRecipientCountryBudgetRequest extends Request {

    protected $redirect;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->request->get('recipientCountryBudget') as $key => $val) {
            foreach ($val['recipientCountry'] as $recipientCountryKey => $recipientCountryVal) {
                $rules['recipientCountryBudget.' . $key . '.recipientCountry.' . $recipientCountryKey . '.code'] = 'required';
                foreach ($recipientCountryVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $rules['recipientCountryBudget.' . $key . '.recipientCountry.' . $recipientCountryKey . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
                }
            }
            foreach ($val['periodStart'] as $periodStartKey => $periodStartVal) {
                $rules['recipientCountryBudget.' . $key . '.periodStart.' . $periodStartKey . '.date'] = 'required';
            }
            foreach ($val['periodEnd'] as $periodEndKey => $periodEndVal) {
                $rules['recipientCountryBudget.' . $key . '.periodEnd.' . $periodEndKey . '.date'] = 'required';
            }
            foreach ($val['value'] as $valueKey => $valueVal) {
                $rules['recipientCountryBudget.' . $key . '.value.' . $valueKey . '.amount'] = 'required|numeric';
                $rules['recipientCountryBudget.' . $key . '.value.' . $valueKey . '.value_date'] = 'required';
            }
            foreach ($val['budgetLine'] as $budgetLineKey => $budgetLineVal) {
                foreach ($budgetLineVal['value'] as $valueKey => $valueVal) {
                    $rules['recipientCountryBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.amount'] = 'required|numeric';
                    $rules['recipientCountryBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.value_date'] = 'required';
                }
                foreach ($budgetLineVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $rules['recipientCountryBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
                }
            }
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('recipientCountryBudget') as $key => $val) {
            foreach ($val['recipientCountry'] as $recipientCountryKey => $recipientCountryVal) {
                $messages['recipientCountryBudget.' . $key . '.recipientCountry.' . $recipientCountryKey . '.code' . '.required'] = sprintf("Code is Required.", $key);
                foreach ($recipientCountryVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $messages['recipientCountryBudget.' . $key . '.recipientCountry.' . $recipientCountryKey . '.narrative.' . $narrativeKey . '.narrative' . '.required'] = sprintf("Narrative is Required.", $key);
                }
            }
            foreach ($val['periodStart'] as $periodStartKey => $periodStartVal) {
                $messages['recipientCountryBudget.' . $key . '.periodStart.' . $periodStartKey . '.date' . '.required'] = sprintf("Period Start is Required.", $key);
            }
            foreach ($val['periodEnd'] as $periodEndKey => $periodEndVal) {
                $messages['recipientCountryBudget.' . $key . '.periodEnd.' . $periodStartKey . '.date' . '.required'] = sprintf("Period End is Required.", $key);
            }
            foreach ($val['value'] as $valueKey => $valueVal) {
                $messages['recipientCountryBudget.' . $key . '.value.' . $valueKey . '.amount' . '.required'] = sprintf("Amount is Required.", $key);
                $messages['recipientCountryBudget.' . $key . '.value.' . $valueKey . '.amount' . '.numeric'] = sprintf("Amount should be numeric.", $key);
                $messages['recipientCountryBudget.' . $key . '.value.' . $valueKey . '.value_date' . '.required'] = sprintf("Date is Required.", $key);
            }
            foreach ($val['budgetLine'] as $budgetLineKey => $budgetLineVal) {
                foreach ($budgetLineVal['value'] as $valueKey => $valueVal) {
                    $messages['recipientCountryBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.amount' . '.required'] = sprintf("Amount is Required.", $key);
                    $messages['recipientCountryBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.amount' . '.numeric'] = sprintf("Amount should be numeric.", $key);
                    $messages['recipientCountryBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.value_date' . '.required'] = sprintf("Date is Required.", $key);
                }
                foreach ($budgetLineVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $messages['recipientCountryBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.narrative.' . $narrativeKey . '.narrative' . '.required'] = sprintf("Title is Required.", $key);
                }
            }
        }
        return $messages;
    }

}
