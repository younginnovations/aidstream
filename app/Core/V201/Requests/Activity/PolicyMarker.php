<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class PolicyMarker
 * @package App\Core\V201\Requests\Activity
 */
class PolicyMarker extends ActivityBaseRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForPolicyMarker($this->get('policy_marker'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForPolicyMarker($this->get('policy_marker'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForPolicyMarker(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $policyMarkerIndex => $policyMarker) {
            $policyMarkerForm                                      = sprintf('policy_marker.%s', $policyMarkerIndex);
            $rules[sprintf('%s.significance', $policyMarkerForm)]  = 'required';
            $rules[sprintf('%s.policy_marker', $policyMarkerForm)] = 'required';
            $rules                                                 = array_merge(
                $rules,
                $this->getRulesForNarrative($policyMarker['narrative'], $policyMarkerForm)
            );
        }


        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getMessagesForPolicyMarker(array $formFields)
    {
        $messages = [];

        foreach ($formFields as $policyMarkerIndex => $policyMarker) {
            $policyMarkerForm                                                  = sprintf('policy_marker.%s', $policyMarkerIndex);
            $messages[sprintf('%s.significance.required', $policyMarkerForm)]  = 'Significance is required';
            $messages[sprintf('%s.policy_marker.required', $policyMarkerForm)] = 'Policy Marker is required';
            $messages                                                          = array_merge(
                $messages,
                $this->getMessagesForNarrative($policyMarker['narrative'], $policyMarkerForm)
            );
        }

        return $messages;
    }
}
