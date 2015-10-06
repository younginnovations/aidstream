<?php namespace App\Core\V201\Requests\Organization;

use App\Http\Requests\Request;

class CreateTotalBudgetRequest extends Request
{

    /**
     * @var Validation
     */
    protected $validation;

    function __construct(Validation $validation)
    {
        $this->validation = $validation;
    }

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
        return $this->addRulesForTotalBudget($this->request->get('totalBudget'));
    }

    public function messages()
    {
        return $this->addMessagesForTotalBudget($this->request->get('totalBudget'));
    }

    /**
     * returns rules for total budget form
     * @param $formFields
     * @return array
     */
    public function addRulesForTotalBudget($formFields)
    {
        $rules = [];
        foreach ($formFields as $totalBudgetIndex => $totalBudget) {
            $totalBudgetForm = 'totalBudget.' . $totalBudgetIndex;
            $rules           = array_merge(
                $rules,
                $this->validation->addRulesForPeriodStart($totalBudget['periodStart'], $totalBudgetForm),
                $this->validation->addRulesForPeriodEnd($totalBudget['periodEnd'], $totalBudgetForm),
                $this->validation->addRulesForValue($totalBudget['value'], $totalBudgetForm),
                $this->validation->addRulesForBudgetLine($totalBudget['budgetLine'], $totalBudgetForm)
            );
        }

        return $rules;
    }

    /**
     * returns messages for total budget form rules
     * @param $formFields
     * @return array
     */
    public function addMessagesForTotalBudget($formFields)
    {
        $messages = [];
        foreach ($formFields as $totalBudgetIndex => $totalBudget) {
            $totalBudgetForm = 'totalBudget.' . $totalBudgetIndex;
            $messages        = array_merge(
                $messages,
                $this->validation->addMessagesForPeriodStart($totalBudget['periodStart'], $totalBudgetForm),
                $this->validation->addMessagesForPeriodEnd($totalBudget['periodEnd'], $totalBudgetForm),
                $this->validation->addMessagesForValue($totalBudget['value'], $totalBudgetForm),
                $this->validation->addMessagesBudgetLine($totalBudget['budgetLine'], $totalBudgetForm)
            );
        }

        return $messages;
    }
}
