<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class TotalBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addPeriodStart('Organization')
            ->addPeriodEnd('Organization')
            ->addValue('Organization')
            ->addBudgetLine('Organization')
            ->addAddMoreButton('add', 'budget_line')
            ->addRemoveThisButton('remove');
    }
}
