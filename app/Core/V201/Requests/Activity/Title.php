<?php namespace App\Core\V201\Requests\Activity;

use Illuminate\Database\DatabaseManager;

/**
 * Class Title
 * @package App\Core\V201\Requests\Activity
 */
class Title extends ActivityBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['narrative.*.narrative'] = 'required';
        $rules['narrative']             = 'unique_lang|unique_default_lang';

        return $rules;
    }

    /**
     * get the error message as required
     * @return array
     */
    public function messages()
    {
        $messages['narrative.*.narrative.required'] = trans('validation.required', ['attribute' => trans('element.title')]);
        $messages['narrative.unique_lang']          = trans('validation.unique', ['attribute' => trans('elementForm.language')]);

        return $messages;
    }
}
