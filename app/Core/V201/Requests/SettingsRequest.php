<?php namespace App\Core\V201\Request;

use App\Http\Requests\Request;

class CreateOrgRecipientOrgBudgetRequest extends Request
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
        return $rules;
    }
}
