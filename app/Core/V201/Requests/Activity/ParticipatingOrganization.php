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
        return $this->addRulesForParticipatingOrg($this->request->get('participating_organization'));
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForParticipatingOrg($this->request->get('participating_organization'));
    }

    /**
     * returns rules for participating organization
     * @param $formFields
     * @return array|mixed
     */
    public function addRulesForParticipatingOrg($formFields)
    {
        $rules = [];

        foreach ($formFields as $participatingOrgIndex => $participatingOrg) {
            $participatingOrgForm                                = 'participating_organization.' . $participatingOrgIndex;
            $rules[$participatingOrgForm . '.organization_role'] = 'required';
            $rules                                               = array_merge(
                $rules,
                $this->addRulesForNarrative(
                    $participatingOrg['narrative'],
                    $participatingOrgForm
                )
            );
        }

        return $rules;
    }

    /**
     * returns messages for participating organization
     * @param $formFields
     * @return array|mixed
     */
    public function addMessagesForParticipatingOrg($formFields)
    {
        $messages = [];

        foreach ($formFields as $participatingOrgIndex => $participatingOrg) {
            $participatingOrgForm                                            = 'participating_organization.' . $participatingOrgIndex;
            $messages[$participatingOrgForm . '.organization_role.required'] = 'Organization role is required';
            $messages                                                        = array_merge(
                $messages,
                $this->addMessagesForNarrative(
                    $participatingOrg['narrative'],
                    $participatingOrgForm
                )
            );
        }

        return $messages;
    }
}
