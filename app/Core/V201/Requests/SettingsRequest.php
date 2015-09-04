<?php namespace App\Core\V201\Requests;

use App\Http\Requests\Request;

class SettingsRequest extends Request
{

    protected $reporting_organization_info;
    protected $default_field_values;

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
        $req = $this->request;
        $this->reporting_organization_info = $req->get('reporting_organization_info')[0];
        $this->default_field_values = $req->get('default_field_values')[0];

        $rules = [];

/*        foreach($this->reporting_organization_info as $key=>$val) {
            $rules["reporting_organization_info.0.$key"] = 'required';
        }
        foreach($this->default_field_values as $key=>$val) {
            $rules["default_field_values.0.$key"] = 'required';
        }*/

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

/*        foreach($this->reporting_organization_info as $key=>$val) {
            $messages["reporting_organization_info.0.$key.required"] = sprintf("The %s is required.", str_replace('_', ' ', $key));
        }
        foreach($this->default_field_values as $key=>$val) {
            $messages["default_field_values.0.$key.required"] = sprintf("The %s is required.", str_replace('_', ' ', $key));
        }*/

        return $messages;
    }
}
