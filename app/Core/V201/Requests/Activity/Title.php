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
        $rules = [];
        
        foreach ($this->get('narrative') as $titleIndex => $title) {
            $rules[sprintf('narrative.%s.narrative', $titleIndex)] = 'required';
        }

        return $rules;
    }

    /**
     * get the error message as required
     * @return array
     */
    public function messages()
    {
        $messages = [];

        foreach ($this->get('narrative') as $titleIndex => $title) {
            $messages[sprintf('narrative.%s.narrative.required', $titleIndex)] = "Title narrative is required";
        }

        return $messages;
    }
}
