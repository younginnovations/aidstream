<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class RelatedActivity
 * @package App\Core\V201\Requests\Activity
 */
class RelatedActivity extends ActivityBaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForRelatedActivity($this->get('related_activity'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForRelatedActivity($this->get('related_activity'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForRelatedActivity(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $relatedActivityIndex => $relatedActivity) {
            $relatedActivityForm                                            = sprintf('related_activity.%s', $relatedActivityIndex);
            $rules[sprintf('%s.relationship_type', $relatedActivityForm)]   = 'required';
            $rules[sprintf('%s.activity_identifier', $relatedActivityForm)] = 'required';
        }

        return $rules;
    }

    protected function getMessagesForRelatedActivity(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $relatedActivityIndex => $relatedActivity) {
            $relatedActivityForm                                                        = sprintf('related_activity.%s', $relatedActivityIndex);
            $messages[sprintf('%s.relationship_type.required', $relatedActivityForm)]   = 'Type of Relationship is Required';
            $messages[sprintf('%s.activity_identifier.required', $relatedActivityForm)] = 'Activity Identifier is Required';
        }

        return $messages;
    }
}
