<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class TotalBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('period_start', 'Organization\PeriodStart', '', [], trans('elementForm.period_start'))
            ->addCollection('period_end', 'Organization\PeriodEnd', '', [], trans('elementForm.period_end'))
            ->addCollection('value', 'Organization\ValueForm', '', [], trans('elementForm.value'))
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line', [], trans('elementForm.budget_line'))
            ->addAddMoreButton('add', 'budget_line')
            ->addRemoveThisButton('remove');
    }
}
