<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class Description
 * @package App\Core\V201\Requests\Activity
 */
class Description extends Request
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
        foreach ($this->request->get('description') as $descriptionIndex => $description) {
            foreach ($description['narrative'] as $narrativeKey => $narrative) {
                $rules['description.' . $descriptionIndex . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
            }
        }

        return $rules;
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('description') as $descriptionIndex => $description) {
            foreach ($description['narrative'] as $narrativeKey => $narrative) {
                $messages['description.' . $descriptionIndex . '.narrative.' . $narrativeKey . '.narrative.' . 'required'] = "Description narrative is required";
            }
        }

        return $messages;
    }
}
