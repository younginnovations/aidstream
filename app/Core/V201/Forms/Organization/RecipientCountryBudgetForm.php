<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientCountryBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('recipient_country', 'Organization\RecipientCountryForm', 'recipient_country')
            ->addPeriodStart('Organization')
            ->addPeriodEnd('Organization')
            ->addValue('Organization')
            ->addBudgetLine('Organization')
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_country_budget');
    }
}
