<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleRecipientOrgBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection(
                'recipient_organization_budget',
                'Organization\RecipientOrgBudgetForm',
                'recipient_organization_budget',
                [],
                'Recipient Organisation Budget'
            )
            ->addAddMoreButton('add_recipient_organization_budget', 'recipient_organization_budget');
    }
}
