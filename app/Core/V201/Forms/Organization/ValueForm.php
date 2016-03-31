<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class ValueForm extends BaseForm
{
    public function buildForm()
    {
        $defaultCurrency = getDefaultCurrency();
        $this->add('amount', 'text', ['help_block' => $this->addHelpText('Organisation_TotalBudget_Value-text')]);

        !(checkDataExists($this->model)) ?: $defaultCurrency = null;
        $this->addSelect('currency', $this->getCodeList('Currency', 'Organization'), 'Currency', $this->addHelpText('Organisation_TotalBudget_Value-currency'), $defaultCurrency);

        $this->add('value_date', 'date', ['help_block' => $this->addHelpText('Organisation_TotalBudget_Value-value_date')]);
    }
}
