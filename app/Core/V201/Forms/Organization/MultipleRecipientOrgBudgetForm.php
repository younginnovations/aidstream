<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleRecipientOrgBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('recipientOrganizationBudget', 'Organization\RecipientOrgBudgetForm', 'recipient_organization_budget')
            ->addAddMoreButton('add_recipient_organization_budget', 'recipient_organization_budget');
    }
}
