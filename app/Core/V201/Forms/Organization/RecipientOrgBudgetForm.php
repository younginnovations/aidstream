<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientOrgBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'recipientOrganization',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\RecipientOrgForm',
                        'label' => false
                    ]
                ]
            )
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
