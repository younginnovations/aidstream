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
        $rules['participating_organization.*.organization_role'] = 'required';

        return $rules;
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        $messages['participating_organization.*.organization_role.required'] = 'Organization Role is required';

        return $messages;
    }
}
