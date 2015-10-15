<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class BudgetLineForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('reference', 'text')
            ->addValue('Organization')
            ->addNarrative('budget_line_narrative')
            ->addAddMoreButton('add', 'budget_line_narrative')
            ->addRemoveThisButton('remove_budget_line');
    }
}
