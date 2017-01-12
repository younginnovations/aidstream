<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class ValueForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add('amount', 'text', ['label' => trans('elementForm.amount'), 'help_block' => $this->addHelpText('Organisation_TotalBudget_Value-text'), 'required' => true])
            ->addSelect('currency', $this->getCodeList('Currency', 'Organization'), trans('elementForm.currency'), $this->addHelpText('Organisation_TotalBudget_Value-currency'))
            ->add('value_date', 'date', ['label' => trans('elementForm.value_date'), 'help_block' => $this->addHelpText('Organisation_TotalBudget_Value-value_date'), 'required' => true]);
    }
}
