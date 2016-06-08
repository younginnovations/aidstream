<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class BudgetOrExpenseLineValueForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('amount', 'text', ['help_block' => $this->addHelpText('Organisation_TotalBudget_Value-text')])
            ->addSelect('currency', $this->getCodeList('Currency', 'Organization'), 'Currency', $this->addHelpText('Organisation_TotalBudget_Value-currency'))
            ->add('value_date', 'date', ['help_block' => $this->addHelpText('Organisation_TotalBudget_Value-value_date')]);
    }
}
