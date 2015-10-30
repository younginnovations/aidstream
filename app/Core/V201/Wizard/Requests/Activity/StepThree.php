<?php namespace App\Core\V201\Wizard\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class StepThree
 * @package App\Core\V201\Wizard\Requests\Activity
 */
class StepThree extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        $rules                    = [];
        $rules['activity_status'] = 'required';
        $rules['start_date']      = 'required';
        $rules['end_date']        = 'required';
        $rules['date_type']       = 'required';

        return $rules;
    }
}
