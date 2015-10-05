<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class ActivityScope
 * @package App\Core\V201\Requests\Activity
 */
class ActivityScope extends Request
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
        $rules                   = [];
        $rules['activity_scope'] = 'required';

        return $rules;
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        $messages                                 = [];
        $messages['activity_scope' . '.required'] = 'Activity scope is required.';

        return $messages;
    }
}
