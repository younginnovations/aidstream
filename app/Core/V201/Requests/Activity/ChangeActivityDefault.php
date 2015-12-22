<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class ChangeActivityDefault
 * @package App\Core\V201\Requests\Activity
 */
class ChangeActivityDefault extends ActivityBaseRequest
{
    /**
     * prepare rules
     * @return mixed
     */
    public function rules()
    {
        $rules['default_currency']  = 'required';
        $rules['default_language']  = 'required';
        $rules['default_hierarchy'] = 'required|numeric';
        $rules['linked_data_uri']   = 'url';

        return $rules;
    }

    /**
     * prepare error messages
     * @return mixed
     */
    public function messages()
    {
        $messages['default_currency.required']  = 'Default Currency is required.';
        $messages['default_language.required']  = 'Default Language is required.';
        $messages['default_hierarchy.required'] = 'Default hierarchy is required.';
        $messages['default_hierarchy.numeric']  = 'Default hierarchy should be numeric.';
        $messages['linked_data_uri.url']        = 'Linked Data uri is invalid';

        return $messages;
    }
}
