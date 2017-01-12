<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class ActivityStatus
 * @package App\Core\V201\Requests\Activity
 */
class ActivityStatus extends Request
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
        $rules                    = [];
        $rules['activity_status'] = 'required';

        return $rules;
    }

    /**
     * prepare the error message
     * @return array
     */
    public function messages()
    {
        $messages                                  = [];
        $messages['activity_status' . '.required'] = trans('validation.required', ['attribute' => trans('element.activity_scope')]);

        return $messages;
    }
}
