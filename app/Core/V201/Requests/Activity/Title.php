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
        $rules['title.*.narrative'] = 'required';

        return $rules;
    }

    /**
     * get the error message as required
     * @return array
     */
    public function messages()
    {
        $messages['title.*.narrative.required'] = 'Title is required';

        return $messages;
    }
}
