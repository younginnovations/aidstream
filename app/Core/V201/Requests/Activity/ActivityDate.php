<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class ActivityDate
 * @package App\Core\V201\Requests\Activity
 */
class ActivityDate extends Request
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
        foreach ($this->request->get('activity_date') as $activityDateIndex => $activityDate) {
            $rules['activity_date.' . $activityDateIndex . '.date'] = 'required';
            $rules['activity_date.' . $activityDateIndex . '.type'] = 'required';
            foreach ($activityDate['narrative'] as $narrativeIndex => $narrative) {
                $rules['activity_date.' . $activityDateIndex . '.narrative.' . $narrativeIndex . '.narrative'] = 'required';
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
        foreach ($this->request->get('activity_date') as $activityDateIndex => $activityDate) {
            $messages['activity_date.' . $activityDateIndex . '.date' . '.required'] = 'Date is required';
            $messages['activity_date.' . $activityDateIndex . '.type' . '.required'] = 'Activity date type is required';
            foreach ($activityDate['narrative'] as $narrativeIndex => $narrative) {
                $messages['activity_date.' . $activityDateIndex . '.narrative.' . $narrativeIndex . '.narrative.' . 'required'] = "Activity date narrative is required";
            }
        }

        return $messages;
    }
}
