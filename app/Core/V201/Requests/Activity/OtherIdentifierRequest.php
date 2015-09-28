<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

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
        foreach ($this->request->get('otherIdentifier') as $key => $val) {
            $rules['otherIdentifier.' . $key . '.reference'] = 'required';
            $rules['otherIdentifier.' . $key . '.type']      = 'required';
            foreach ($val['ownerOrg'] as $ownerOrgKey => $ownerOrgVal) {
                $rules['otherIdentifier.' . $key . '.ownerOrg.' . $ownerOrgKey . '.reference'] = 'required';
                foreach ($ownerOrgVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $rules['otherIdentifier.' . $key . '.ownerOrg.' . $ownerOrgKey . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
                }
            }
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('otherIdentifier') as $key => $val) {
            $messages['otherIdentifier.' . $key . '.reference' . '.required'] = sprintf("Reference is Required.", $key);
            $messages['otherIdentifier.' . $key . '.type' . '.required']      = sprintf("Type is Required.", $key);
            foreach ($val['ownerOrg'] as $ownerOrgKey => $ownerOrgVal) {
                $messages['otherIdentifier.' . $key . '.ownerOrg.' . $ownerOrgKey . '.reference' . '.required'] = sprintf(
                    "Reference is Required.",
                    $key
                );
                foreach ($ownerOrgVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $messages['otherIdentifier.' . $key . '.ownerOrg.' . $ownerOrgKey . '.narrative.' . $narrativeKey . '.narrative' . '.required'] = sprintf(
                        "Narrative text is Required.",
                        $key
                    );
                }
            }
        }

        return $messages;
    }

}
