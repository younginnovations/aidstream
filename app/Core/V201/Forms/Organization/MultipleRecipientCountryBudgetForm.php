<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleRecipientCountryBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection(
                'recipient_country_budget',
                'Organization\RecipientCountryBudgetForm',
                'recipient_country_budget'
            )
            ->addAddMoreButton('add_recipient_country_budget', 'recipient_country_budget');
    }
}
