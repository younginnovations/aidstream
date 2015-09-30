<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class MultipleRecipientOrgBudgetForm extends Form
{

    public function buildForm()
    {
        $this
            ->add(
                'recipientOrganizationBudget',
                'collection',
                [
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\RecipientOrgBudgetForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form recipient_organization_budget'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'recipient_organization_budget'
                    ]
                ]
            );
    }

}
