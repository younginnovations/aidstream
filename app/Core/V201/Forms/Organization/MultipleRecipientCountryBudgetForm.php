<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleRecipientCountryBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'recipientCountryBudget',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\RecipientCountryBudgetForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form recipient_country_budget'
                    ]
                ]
            )
            ->addAddMoreButton('add_recipient_country_budget', 'recipient_country_budget');
    }
}
