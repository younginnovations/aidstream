<?php namespace App\Core\V201\Requests\Activity;


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
        $rules['narrative']             = 'unique_lang';

        return $rules;
    }

    /**
     * get the error message as required
     * @return array
     */
    public function messages()
    {
        $messages['narrative.*.narrative.required'] = 'Title is required';
        $messages['narrative.unique_lang']          = 'Language should be unique.';

        return $messages;
    }
}
