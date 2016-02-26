<?php namespace App\Core\V201\Requests;

use App\Http\Requests\Request;

class SettingsRequest extends Request
{
    protected $reporting_organization_info;

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
        $req                               = $this;
        $this->reporting_organization_info = $req->get('reporting_organization_info')[0];
        $rules                             = [];
        foreach ($this->reporting_organization_info as $key => $val) {
            $rules["reporting_organization_info.0.$key"] = 'required';
        }

        $rules["reporting_organization_info.0.narrative.0.narrative"] = 'required';
        $rules["default_field_values.0.linked_data_uri"]              = 'url';
        $rules["default_field_values.0.default_currency"]             = 'required';
        $rules["default_field_values.0.default_language"]             = 'required';
        $rules["default_field_values.0.default_hierarchy"]            = 'numeric';

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

        foreach ($this->reporting_organization_info as $key => $val) {
            $messages["reporting_organization_info.0.$key.required"] = sprintf("The %s is required.", str_replace('_', ' ', $key));
        }

        $messages["reporting_organization_info.0.narrative.0.narrative.required"] = 'At least one Organization Name is required.';
        $messages["default_field_values.0.linked_data_uri.url"]                   = "Linked data uri is invalid.";
        $messages["default_field_values.0.default_currency.required"]             = 'Default Currency is required';
        $messages["default_field_values.0.default_language.required"]             = 'Default Language is required';
        $messages["default_field_values.0.default_hierarchy.numeric"]             = 'Hierarchy should be numeric';

        return $messages;
    }
}
