<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientCountryBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('recipient_country', 'Organization\RecipientCountryForm', 'recipient_country', [], trans('elementForm.recipient_country'))
            ->addCollection('period_start', 'Organization\PeriodStart', '', [], trans('elementForm.period_start'))
            ->addCollection('period_end', 'Organization\PeriodEnd', '', [], trans('elementForm.period_end'))
            ->addCollection('value', 'Organization\ValueForm', '', [], trans('elementForm.value'))
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line', [], trans('elementForm.budget_line'))
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_country_budget');
    }
}
