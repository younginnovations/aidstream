<?php namespace App\Core\V201\Requests\Activity;

/**
 * Class ParticipatingOrganization
 * @package App\Core\V201\Requests\Activity
 */
class ParticipatingOrganization extends ActivityBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForParticipatingOrg($this->get('participating_organization'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForParticipatingOrg($this->get('participating_organization'));
    }

    /**
     * returns rules for participating organization
     * @param $formFields
     * @return array|mixed
     */
    public function getRulesForParticipatingOrg($formFields)
    {
        $rules = [];

        foreach ($formFields as $participatingOrgIndex => $participatingOrg) {
            $participatingOrgForm                                = 'participating_organization.' . $participatingOrgIndex;
            $rules[$participatingOrgForm . '.organization_role'] = 'required';
            $identifier                                          = $participatingOrgForm . '.identifier';
            $narrativeKeys                                       = array_keys($participatingOrg['narrative']);
            $narrative                                           = sprintf('%s.narrative.%s.narrative', $participatingOrgForm, $narrativeKeys[0]);
            $rules[$identifier]                                  = 'exclude_operators|required_without:' . $narrative;
            $rules[$narrative][]                                 = 'required_without:' . $identifier;
            $rules                                               = array_merge_recursive(
                $rules,
                $this->getRulesForNarrative($participatingOrg['narrative'], $participatingOrgForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for participating organization
     * @param $formFields
     * @return array|mixed
     */
    public function getMessagesForParticipatingOrg($formFields)
    {
        $messages = [];

        foreach ($formFields as $participatingOrgIndex => $participatingOrg) {
            $participatingOrgForm                                            = 'participating_organization.' . $participatingOrgIndex;
            $messages[$participatingOrgForm . '.organization_role.required'] = 'Organization role is required';
            $identifier                                                      = $participatingOrgForm . '.identifier';
            $narrativeKeys                                                   = array_keys($participatingOrg['narrative']);
            $narrative                                                       = sprintf('%s.narrative.%s.narrative', $participatingOrgForm, $narrativeKeys[0]);
            $messages[$identifier . '.required_without']                     = 'Identifier is required when Narrative is not present.';
            $messages[$narrative . '.required_without']                      = 'Narrative is required when Identifier is not present.';
            $messages                                                        = array_merge(
                $messages,
                $this->getMessagesForNarrative($participatingOrg['narrative'], $participatingOrgForm)
            );
        }

        return $messages;
    }
}
