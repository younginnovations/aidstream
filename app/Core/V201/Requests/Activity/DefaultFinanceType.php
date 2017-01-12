<?php namespace App\Core\V201\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class DefaultFinanceType
 * @package App\Core\V201\Requests\Activity
 */
class DefaultFinanceType extends Request
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
        $rules['default_finance_type'] = 'required';

        return $rules;
    }

    public function messages()
    {
        $messages['default_finance_type.required'] = trans('validation.required', ['attribute' => trans('element.default_finance_type')]);

        return $messages;
    }

}
