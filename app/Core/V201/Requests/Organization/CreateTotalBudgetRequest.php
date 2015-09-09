<?php namespace App\Core\V201\Requests\Organization;

use App\Http\Requests\Request;
use App\Models\OrganizationData;
use Illuminate\Foundation\Http\FormRequest;

class CreateTotalBudgetRequest extends Request {

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
        foreach ($this->request->get('totalBudget') as $key => $val) {
            foreach ($val['periodStart'] as $periodStartKey => $periodStartVal) {
                $rules['totalBudget.' . $key . '.periodStart.' . $periodStartKey . '.date'] = 'required';
            }
            foreach ($val['periodEnd'] as $periodEndKey => $periodEndVal) {
                $rules['totalBudget.' . $key . '.periodEnd.' . $periodEndKey . '.date'] = 'required';
            }
            foreach ($val['value'] as $valueKey => $valueVal) {
                $rules['totalBudget.' . $key . '.value.' . $valueKey . '.amount'] = 'required|numeric';
                $rules['totalBudget.' . $key . '.value.' . $valueKey . '.value_date'] = 'required';
            }
            foreach ($val['budgetLine'] as $budgetLineKey => $budgetLineVal) {
                foreach ($budgetLineVal['value'] as $valueKey => $valueVal) {
                    $rules['totalBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.amount'] = 'required|numeric';
                    $rules['totalBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.value_date'] = 'required';
                }
                foreach ($budgetLineVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $rules['totalBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
                }
            }
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('totalBudget') as $key => $val) {
            foreach ($val['periodStart'] as $periodStartKey => $periodStartVal) {
                $messages['totalBudget.' . $key . '.periodStart.' . $periodStartKey . '.date' . '.required'] = sprintf("Period Start is Required.", $key);
            }
            foreach ($val['periodEnd'] as $periodEndKey => $periodEndVal) {
                $messages['totalBudget.' . $key . '.periodEnd.' . $periodStartKey . '.date' . '.required'] = sprintf("Period End is Required.", $key);
            }
            foreach ($val['value'] as $valueKey => $valueVal) {
                $messages['totalBudget.' . $key . '.value.' . $valueKey . '.amount' . '.required'] = sprintf("Amount is Required.", $key);
                $messages['totalBudget.' . $key . '.value.' . $valueKey . '.amount' . '.numeric'] = sprintf("Amount should be numeric.", $key);
                $messages['totalBudget.' . $key . '.value.' . $valueKey . '.value_date' . '.required'] = sprintf("Date is Required.", $key);
            }
            foreach ($val['budgetLine'] as $budgetLineKey => $budgetLineVal) {
                foreach ($budgetLineVal['value'] as $valueKey => $valueVal) {
                    $messages['totalBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.amount' . '.required'] = sprintf("Amount is Required.", $key);
                    $messages['totalBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.amount' . '.numeric'] = sprintf("Amount should be numeric.", $key);
                    $messages['totalBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.value.' . $valueKey . '.value_date' . '.required'] = sprintf("Date is Required.", $key);
                }
                foreach ($budgetLineVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $messages['totalBudget.' . $key . '.budgetLine.' . $budgetLineKey . '.narrative.' . $narrativeKey . '.narrative' . '.required'] = sprintf("Title is Required.", $key);
                }
            }
        }
        return $messages;
    }

}
