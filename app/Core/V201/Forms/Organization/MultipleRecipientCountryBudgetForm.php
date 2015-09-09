<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class MultipleRecipientCountryBudgetForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('recipientCountryBudget', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\RecipientCountryBudgetForm',
                    'label' => false,
                ],
                'wrapper' => false
            ]);
    }
}