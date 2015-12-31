<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class LegacyData
 * @package App\Core\V201\Requests\Activity
 */
class LegacyData extends ActivityBaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForLegacyData($this->get('legacy_data'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForLegacyData($this->get('legacy_data'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForLegacyData(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $legacyDataIndex => $legacyData) {
            $legacyDataForm                              = sprintf('legacy_data.%s', $legacyDataIndex);
            $rules[sprintf('%s.name', $legacyDataForm)]  = 'required';
            $rules[sprintf('%s.value', $legacyDataForm)] = 'required';
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getMessagesForLegacyData(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $legacyDataIndex => $legacyData) {
            $legacyDataForm                                          = sprintf('legacy_data.%s', $legacyDataIndex);
            $messages[sprintf('%s.name.required', $legacyDataForm)]  = 'Name is Required';
            $messages[sprintf('%s.value.required', $legacyDataForm)] = 'Value is Required';
        }

        return $messages;
    }
}
