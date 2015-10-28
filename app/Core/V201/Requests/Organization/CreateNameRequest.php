<?php namespace App\Core\V201\Requests\Organization;

use App\Http\Requests\Request;

class CreateNameRequest extends Request
{

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
