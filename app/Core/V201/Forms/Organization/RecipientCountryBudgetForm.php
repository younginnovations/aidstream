<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientCountryBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('recipient_country', 'Organization\RecipientCountryForm', 'recipient_country')
            ->addCollection('period_start', 'Organization\PeriodStart')
            ->addCollection('period_end', 'Organization\PeriodEnd')
            ->addCollection('value', 'Organization\ValueForm')
            ->addCollection('budget_line', 'Organization\BudgetLineForm', 'budget_line')
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_country_budget');
    }
}
