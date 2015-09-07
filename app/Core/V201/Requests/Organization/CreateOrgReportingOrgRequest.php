<?php namespace App\Core\V201\Request\Organization;

use App\Http\Requests\Request;
use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrgReportingOrgRequest extends Request {

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
		foreach ($this->request->get('reportingOrg') as $key => $val) {
			foreach ($val['narrative'] as $narrativeKey => $narrativeVal) {
				$rules['reportingOrg.' . $key . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
				$rules['reportingOrg.' . $key . '.narrative.' . $narrativeKey . '.language'] = 'required';
			}
		}
		return $rules;
	}

	/**
	 * Get the Validation Error message
	 * @return array
	 */
	public function messages()
	{
		$messages = [];
		foreach ($this->request->get('reportingOrg') as $key => $val) {
			foreach ($val['narrative'] as $narrativeKey => $narrativeVal) {
				$messages['reportingOrg.' . $key . '.narrative.' . $narrativeKey . '.narrative.required'] = sprintf('Narrative Title %d is required',
					$key);
				$messages['reportingOrg.' . $key . '.narrative.' . $narrativeKey . '.language.required'] = sprintf('Narrative Language %d is required',
					$key);
			}
		}
		return $messages;
	}
}
