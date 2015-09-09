<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class MultipleTotalBudgetForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('totalBudget', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\TotalBudgetForm',
                    'label' => false,
                ]
            ]);
    }
}