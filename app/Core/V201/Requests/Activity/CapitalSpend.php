<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class CapitalSpend
 * @package App\Core\V201\Requests\Activity
 */
class CapitalSpend extends Request
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
     * get the validation rules that apply to the activity request.
     * @return array
     */
    public function rules()
    {
        $rules['capital_spend'] = 'required|numeric|max:100|min:0';

        return $rules;
    }

    public function messages()
    {
        $messages['capital_spend.required'] = trans('validation.required', ['attribute' => trans('element.capital_spend')]);
        $messages['capital_spend.numeric']  = trans('validation.numeric', ['attribute' => trans('element.capital_spend')]);
        $messages['capital_spend.max']      = trans('validation.max.numeric', ['attribute' => trans('element.capital_spend'), 'max' => 100]);
        $messages['capital_spend.min']      = trans('validation.max.numeric', ['attribute' => trans('element.capital_spend'), 'min' => 0]);

        return $messages;
    }

}
