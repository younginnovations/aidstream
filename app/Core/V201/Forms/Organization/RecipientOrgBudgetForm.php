<?php namespace App\Core\V201\Forms\Organization;

use App\Core\ProtoName;
use Kris\LaravelFormBuilder\Form;

class RecipientOrgBudgetForm extends Form
{
    public function buildForm()
    {
        $this
        	->add('recipientOrganization', 'collection',[
        		'type' => 'form',
        		'prototype' => true,
        		'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\RecipientOrgForm',
                    'label' => false
                ]
        	])
        	->add('narrative', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                    'label' => false
                ]
            ])
            ->add('periodStart', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\PeriodStartForm',
                    'label' => false,
                ]
            ])
            ->add('periodEnd', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\PeriodEndForm',
                    'label' => false,
                ]
            ])
            ->add('value', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\ValueForm',
                    'label' => false,
                ]
            ])
            ->add('budgetLine', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\BudgetLineForm',
                    'label' => false,
                ],
            ]);

        new ProtoName($this);

    }
}