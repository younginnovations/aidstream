<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class TotalBudgetForm extends Form
{
    public function buildForm()
    {
        $this
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
    }
}