<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\PolicyMaker as V201PolicyMaker;

/**
 * Class PolicyMaker
 * @package App\Core\V202\Requests\Activity
 */
class PolicyMaker extends V201PolicyMaker
{
    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForPolicyMaker(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $policyMakerIndex => $policyMaker) {
            $policyMakerForm                                       = sprintf('policy_marker.%s', $policyMakerIndex);
            $rules[sprintf('%s.vocabulary_uri', $policyMakerForm)] = 'url';
            $rules[sprintf('%s.policy_marker', $policyMakerForm)]  = 'required';
            $rules                                                 = array_merge(
                $rules,
                $this->getRulesForNarrative($policyMaker['narrative'], $policyMakerForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getMessagesForPolicyMaker(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $policyMakerIndex => $policyMaker) {
            $policyMakerForm                                                  = sprintf('policy_marker.%s', $policyMakerIndex);
            $messages[sprintf('%s.vocabulary_uri.url', $policyMakerForm)]     = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('%s.policy_marker.required', $policyMakerForm)] = 'Policy Marker is required';
            $messages                                                         = array_merge(
                $messages,
                $this->getMessagesForNarrative($policyMaker['narrative'], $policyMakerForm)
            );
        }

        return $messages;
    }
}
