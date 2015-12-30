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
        return $this->getRulesForDescription($this->get('description'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForDescription($this->get('description'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForDescription(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $descriptionIndex => $description) {
            $descriptionForm = sprintf('description.%s', $descriptionIndex);
            $rules           = array_merge(
                $rules,
                $this->getRulesForNarrative($description['narrative'], $descriptionForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getMessagesForDescription(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $descriptionIndex => $description) {
            $descriptionForm = sprintf('description.%s', $descriptionIndex);
            $messages        = array_merge(
                $messages,
                $this->getMessagesForNarrative($description['narrative'], $descriptionForm)
            );
        }

        return $messages;
    }
}
