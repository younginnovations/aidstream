<?php namespace App\Core\V201\Requests\Organization;

/**
 * Class CreateNameRequest
 * @package App\Core\V201\Requests\Organization
 */
class CreateNameRequest extends OrganizationBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules         = [];
        $rules['name'] = 'unique_lang';
        foreach ($this->get('name') as $nameIndex => $name) {
            $rules[sprintf('name.%s.narrative', $nameIndex)] = 'required';
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        $messages                     = [];
        $messages['name.unique_lang'] = 'Languages should be unique.';
        foreach ($this->get('name') as $nameIndex => $name) {
            $messages[sprintf('name.%s.narrative.required', $nameIndex)] = 'Text is required.';
        }

        return $messages;
    }
}
