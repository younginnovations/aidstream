<?php namespace App\Core\V201\Requests\Organization;

/**
 * Class CreateNameRequest
 * @package App\Core\V201\Requests\Organization
 */
class CreateNameRequest extends OrganizationBaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->request->get('name') as $nameIndex => $name) {
            $rules['name.' . $nameIndex . '.narrative'] = 'required|max:255';
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('name') as $nameIndex => $name) {
            $messages['name.' . $nameIndex . '.narrative' . '.required'] = sprintf(
                "Narrative is Required.",
                $nameIndex
            );
            $messages['name.' . $nameIndex . '.narrative' . '.max']      = sprintf("Max(255) Narrative .", $nameIndex);
        }

        return $messages;
    }
}
