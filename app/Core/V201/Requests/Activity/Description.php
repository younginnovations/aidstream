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
            $rules[sprintf('%s.type', $descriptionForm)] = 'required';
            $rules           = array_merge(
                $rules,
                $this->getRulesForRequiredNarrative($description['narrative'], $descriptionForm)
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
            $messages[sprintf('%s.type.required', $descriptionForm)] = 'Description Type is required.';
            $messages        = array_merge(
                $messages,
                $this->getMessagesForRequiredNarrative($description['narrative'], $descriptionForm)
            );
        }

        return $messages;
    }
}
