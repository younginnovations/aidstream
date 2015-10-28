<?php namespace App\Core\V201\Requests\Activity;


/**
 * Class Description
 * @package App\Core\V201\Requests\Activity
 */
class Description extends ActivityBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->addRulesForDescription($this->request->get('description'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForDescription($this->request->get('description'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addRulesForDescription(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $descriptionIndex => $description) {
            $descriptionForm = sprintf('description.%s', $descriptionIndex);
            $rules           = array_merge(
                $rules,
                $this->addRulesForNarrative($description['narrative'], $descriptionForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addMessagesForDescription(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $descriptionIndex => $description) {
            $descriptionForm = sprintf('description.%s', $descriptionIndex);
            $messages        = array_merge(
                $messages,
                $this->addMessagesForNarrative($description['narrative'], $descriptionForm)
            );
        }

        return $messages;
    }
}
