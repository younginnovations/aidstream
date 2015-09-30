<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

class Title extends Request
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
        foreach ($this->request->get('narrative') as $titleIndex => $title) {
            $rules['narrative.' . $titleIndex . '.narrative'] = 'required';
        }

        return $rules;
    }

    /**
     * get the error message as required
     * @return array
     */
    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('narrative') as $titleIndex => $title) {
            $messages['narrative.' . $titleIndex . '.narrative' . '.required'] = "Title narrative is required";
        }

        return $messages;
    }
}
