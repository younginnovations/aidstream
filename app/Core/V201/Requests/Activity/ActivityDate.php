<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class ActivityDate
 * @package App\Core\V201\Requests\Activity
 */
class ActivityDate extends ActivityBaseRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return $this->addRulesForActivityDate($this->request->get('activity_date'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForActivityDate($this->request->get('activity_date'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addRulesForActivityDate(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $activityDateIndex => $activityDate) {
            $activityDateForm                             = sprintf('activity_date.%s', $activityDateIndex);
            $rules[sprintf('%s.date', $activityDateForm)] = 'required';
            $rules[sprintf('%s.type', $activityDateForm)] = 'required';
            $rules                                        = array_merge(
                $rules,
                $this->addRulesForNarrative($activityDate['narrative'], $activityDateForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addMessagesForActivityDate(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $activityDateIndex => $activityDate) {
            $activityDateForm                                         = sprintf('activity_date.%s', $activityDateIndex);
            $messages[sprintf('%s.date.required', $activityDateForm)] = 'Date is required';
            $messages[sprintf('%s.type.required', $activityDateForm)] = 'Type is required';
            $messages                                                 = array_merge(
                $messages,
                $this->addMessagesForNarrative($activityDate['narrative'], $activityDateForm)
            );
        }

        return $messages;
    }
}
