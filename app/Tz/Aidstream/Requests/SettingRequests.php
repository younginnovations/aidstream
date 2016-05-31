<?php namespace App\Tz\Aidstream\Requests;

use App\Http\Requests\Request;

/**
 * Class SettingRequests
 * @package App\Tz\Aidstream\Requests
 */
class SettingRequests extends Request
{

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Rules for Settings
     * @return array
     */
    public function rules()
    {
        $rules                             = [];
        $rules['reporting_org_identifier'] = 'required';
        $rules['reporting_org_type']       = 'required';
        $rules['narrative']                = 'required';
        $rules['language']                 = 'required';
        $rules['default_currency']         = 'required';
        $rules['default_language']         = 'required';

        return $rules;
    }

    /**
     * Messages for Settings
     * @return array
     */
    public function messages()
    {
        $messages                                           = [];
        $messages['reporting_org_identifier' . '.required'] = 'Reporting Organization is required.';
        $messages['reporting_org_type' . '.required']       = 'Reporting Organization Type is required.';
        $messages['narrative' . '.required']                = 'Text is required.';
        $messages['language' . '.required']                 = 'Language is required.';
        $messages['default_currency' . '.required']         = 'Default Currency is required.';
        $messages['default_language' . '.required']         = 'Default Language is required.';

        return $messages;

    }
}
