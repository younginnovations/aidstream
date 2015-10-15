<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleRecipientOrgBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'recipientOrganizationBudget',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\RecipientOrgBudgetForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form recipient_organization_budget'
                    ]
                ]
            )
            ->addAddMoreButton('add_recipient_organization_budget', 'recipient_organization_budget');
    }
}
