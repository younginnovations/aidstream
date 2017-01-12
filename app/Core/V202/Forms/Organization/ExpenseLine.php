<?php namespace App\Core\V202\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class ExpenseLine
 * @package App\Core\V202\Forms\Organization
 */
class ExpenseLine extends BaseForm
{
    /**
     * build organization Expense Line form
     */
    public function buildForm()
    {
        $this
            ->add('reference', 'text', ['label' => trans('elementForm.reference')])
            ->addCollection('value', 'Organization\BudgetOrExpenseLineValueForm', '', [], trans('elementForm.value'))
            ->addNarrative('expense_line_narrative')
            ->addAddMoreButton('add', 'expense_line_narrative')
            ->addRemoveThisButton('remove');
    }
}
