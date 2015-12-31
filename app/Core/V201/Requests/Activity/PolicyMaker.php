<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class PolicyMaker
 * @package App\Core\V201\Requests\Activity
 */
class PolicyMaker extends ActivityBaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForPolicyMaker($this->get('policy_maker'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForPolicyMaker($this->get('policy_maker'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForPolicyMaker(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $policyMakerIndex => $policyMaker) {
            $policyMakerForm                                      = sprintf('policy_maker.%s', $policyMakerIndex);
            $rules[sprintf('%s.significance', $policyMakerForm)]  = 'required';
            $rules[sprintf('%s.policy_marker', $policyMakerForm)] = 'required';
            $rules                                                = array_merge(
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
            $policyMakerForm                                                  = sprintf(
                'policy_maker.%s',
                $policyMakerIndex
            );
            $messages[sprintf('%s.significance.required', $policyMakerForm)]  = 'Significance is required';
            $messages[sprintf('%s.policy_marker.required', $policyMakerForm)] = 'Policy Marker is required';
            $messages                                                         = array_merge(
                $messages,
                $this->getMessagesForNarrative($policyMaker['narrative'], $policyMakerForm)
            );
        }

        return $messages;
    }
}
