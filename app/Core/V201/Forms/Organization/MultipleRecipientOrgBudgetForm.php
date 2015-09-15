<?php namespace App\Core\V201\Forms\Organization;

use App\Core\ProtoName;
use Kris\LaravelFormBuilder\Form;

class MultipleRecipientOrgBudgetForm extends Form
{

    public function buildForm()
    {
        $this
            ->add('recipientOrganizationBudget', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\RecipientOrgBudgetForm',
                    'label' => false,
                ],
                'label' => false,
                'wrapper' => false
            ]);

        new ProtoName($this);

    }

}