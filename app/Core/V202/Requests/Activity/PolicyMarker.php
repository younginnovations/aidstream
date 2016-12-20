<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\PolicyMarker as V201PolicyMarker;

/**
 * Class PolicyMarker
 * @package App\Core\V202\Requests\Activity
 */
class PolicyMarker extends V201PolicyMarker
{
    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForPolicyMarker(array $formFields)
    {
        $rules = [];

        foreach ($formFields as $policyMarkerIndex => $policyMarker) {
            $policyMarkerForm                                       = sprintf('policy_marker.%s', $policyMarkerIndex);
            $rules[sprintf('%s.vocabulary_uri', $policyMarkerForm)] = 'url';
            $rules[sprintf('%s.policy_marker', $policyMarkerForm)]  = 'required';
            $rules                                                  = array_merge(
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
            $messages[sprintf('%s.vocabulary_uri.url', $policyMarkerForm)]     = trans('validation.url');
            $messages[sprintf('%s.policy_marker.required', $policyMarkerForm)] = trans('validation.required', ['attribute' => trans('element.policy_marker')]);
            $messages                                                          = array_merge(
                $messages,
                $this->getMessagesForNarrative($policyMarker['narrative'], $policyMarkerForm)
            );
        }

        return $messages;
    }
}
