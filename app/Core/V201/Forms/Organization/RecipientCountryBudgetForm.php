<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class RecipientCountryBudgetForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'recipientCountry',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\RecipientCountryForm',
                        'label' => false
                    ],
                    'wrapper' => [
                        'class' => 'collection_form recipient_country'
                    ]
                ]
            )
            ->addPeriodStart('Organization')
            ->addPeriodEnd('Organization')
            ->addValue('Organization')
            ->addBudgetLine('Organization')
            ->addAddMoreButton('add_budget_line', 'budget_line')
            ->addRemoveThisButton('remove_recipient_country_budget');
    }
}