<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class OtherIdentifierRequest
 * @package App\Core\V201\Requests\Activity
 */
class OtherIdentifierRequest extends Request
{

    protected $redirect;

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
        $rules = [];
        foreach ($this->request->get('otherIdentifier') as $otherIdentifierIndex => $otherIdentifier) {
            $rules['otherIdentifier.' . $otherIdentifierIndex . '.reference'] = 'required';
            $rules['otherIdentifier.' . $otherIdentifierIndex . '.type']      = 'required';
            foreach ($otherIdentifier['ownerOrg'] as $ownerOrgKey => $ownerOrgVal) {
                $rules['otherIdentifier.' . $otherIdentifierIndex . '.ownerOrg.' . $ownerOrgKey . '.reference'] = 'required';
                foreach ($ownerOrgVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $rules['otherIdentifier.' . $otherIdentifierIndex . '.ownerOrg.' . $ownerOrgKey . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('otherIdentifier') as $otherIdentifierIndex => $otherIdentifier) {
            $messages['otherIdentifier.' . $otherIdentifierIndex . '.reference' . '.required'] = "Reference is Required.";
            $messages['otherIdentifier.' . $otherIdentifierIndex . '.type' . '.required']      = "Type is Required.";
            foreach ($otherIdentifier['ownerOrg'] as $ownerOrgKey => $ownerOrgVal) {
                $messages['otherIdentifier.' . $otherIdentifierIndex . '.ownerOrg.' . $ownerOrgKey . '.reference' . '.required'] = "Reference is Required.";
                foreach ($ownerOrgVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $messages['otherIdentifier.' . $otherIdentifierIndex . '.ownerOrg.' . $ownerOrgKey . '.narrative.' . $narrativeKey . '.narrative' . '.required'] = "Narrative text is Required.";
                }
            }
        }

        return $messages;
    }

}
