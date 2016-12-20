<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class BudgetLineForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('reference', 'text', ['label' => trans('elementForm.reference'), 'help_block' => $this->addHelpText('Organisation_TotalBudget_BudgetLine-ref')])
            ->addCollection('value', 'Organization\BudgetOrExpenseLineValueForm', '', [], trans('elementForm.value'))
            ->addNarrative('budget_line_narrative')
            ->addAddMoreButton('add', 'budget_line_narrative')
            ->addRemoveThisButton('remove_budget_line');
    }
}
