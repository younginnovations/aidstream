<?php namespace App\Core\V201\Wizard\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class StepTwo
 * @package App\Core\V201\Wizard\Requests\Activity
 */
class StepTwo extends Request
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
        $rules            = [];
        $rules['title']   = 'required';
        $rules['general'] = 'required';

        return $rules;
    }
}
