<?php namespace App\Core\V201\Requests\Organization;

use App\Http\Requests\Request;
//use Illuminate\Http\Request;

class CreateOrgRecipientOrgBudgetRequest extends Request {

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
//		dd($this->request);
		foreach ($this->request->get('recipientOrganizationBudget') as $key => $val) {
			foreach ($val['periodStart'] as $periodStartKey => $periodStartVal) {
				$rules['recipientOrganizationBudget.' . $key . '.periodStart.' . $periodStartKey . '.date'] = 'required';
			}
			foreach ($val['periodEnd'] as $periodEndKey => $periodEndVal) {
				$rules['recipientOrganizationBudget.' . $key . '.periodEnd.' . $periodEndKey . '.date'] = 'required';
			}
			foreach ($val['value'] as $valueKey => $valueVal) {
				$rules['recipientOrganizationBudget.' . $key . '.value.' . $valueKey . '.amount'] = 'required|numeric';
				$rules['recipientOrganizationBudget.' . $key . '.value.' . $valueKey . '.value_date'] = 'required';
			}
			foreach ($val['narrative'] as $narrativeKey => $narrativeVal) {
				$rules['recipientOrganizationBudget.' . $key . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
			}
			
		}
		return $rules;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function messages()
	{
		$messages = [];
		foreach ($this->request->get('recipientOrganizationBudget') as $key => $val) {
			foreach ($val['periodStart'] as $periodStartKey => $periodStartVal) {
				$messages['recipientOrganizationBudget.' . $key . '.periodStart.' . $periodStartKey . '.date' . '.required'] = "Period start is required.";
			}
			foreach ($val['periodEnd'] as $periodEndKey => $periodEndVal) {
				$messages['recipientOrganizationBudget.' . $key . '.periodEnd.' . $periodEndKey . '.date' . '.required'] = "Period end is required.";
			}
			foreach ($val['value'] as $valueKey => $valueVal) {
				$messages['recipientOrganizationBudget.' . $key . '.value.' . $valueKey . '.amount' . '.required'] = "Amount is required.";
				$messages['recipientOrganizationBudget.' . $key . '.value.' . $valueKey . '.amount' . '.numeric'] = "Amount should be numeric.";
				$messages['recipientOrganizationBudget.' . $key . '.value.' . $valueKey . '.value_date' . '.required'] = "Date is required.";
			}
			foreach ($val['narrative'] as $narrativeKey => $narrativeVal) {
				$messages['recipientOrganizationBudget.' . $key . '.narrative.' . $narrativeKey . '.narrative' . '.required'] = "Title is Required.";
			}

		}
		return $messages;
	}

}
