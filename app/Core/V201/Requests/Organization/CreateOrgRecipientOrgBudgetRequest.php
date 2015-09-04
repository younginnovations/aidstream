<?php namespace App\Core\V201\Request\Organization;

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

}
