<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class TotalBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('period_start', 'Organization\PeriodStart')
            ->addCollection('period_end', 'Organization\PeriodEnd')
            ->addCollection('value', 'Organization\ValueForm')
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line')
            ->addAddMoreButton('add', 'budget_line')
            ->addRemoveThisButton('remove');
    }
}
