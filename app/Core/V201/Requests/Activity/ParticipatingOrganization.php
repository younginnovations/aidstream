<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class ParticipatingOrganization
 * @package App\Core\V201\Requests\Activity
 */
class ParticipatingOrganization extends Request
{
    /**
     * @var
     */
    protected $validation;

    /**
     * @param Validation $validation
     */
    function __construct(Validation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
            $rules                                               = $this->validation->addRulesForNarrative(
                $participatingOrg['narrative'],
                $participatingOrgForm,
                $rules
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
            $messages                                                        = $this->validation->addMessagesForNarrative(
                $participatingOrg['narrative'],
                $participatingOrgForm,
                $messages
            );
        }

        return $messages;
    }
}
