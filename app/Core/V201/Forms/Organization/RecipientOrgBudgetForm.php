<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientOrgBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('recipientOrganization', 'Organization\RecipientOrgForm')
            ->addNarrative('narrative')
            ->addAddMoreButton('add_narrative', 'narrative')
            ->addPeriodStart('Organization')
            ->addPeriodEnd('Organization')
            ->addValue('Organization')
            ->addBudgetLine('Organization')
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_org_budget');
    }
}
