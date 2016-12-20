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
        $messages['default_currency.required']  = trans('validation.required', ['attribute' => trans('elementForm.default_currency')]);
        $messages['default_language.required']  = trans('validation.required', ['attribute' => trans('elementForm.default_language')]);
        $messages['default_hierarchy.required'] = trans('validation.required', ['attribute' => trans('elementForm.default_hierarchy')]);
        $messages['default_hierarchy.numeric']  = trans('validation.numeric', ['attribute' => trans('elementForm.default_hierarchy')]);
        $messages['linked_data_uri.url']        = trans('validation.url', ['attribute' => trans('elementForm.linked_data_uri')]);

        return $messages;
    }
}
