<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class BudgetLineForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('reference', 'text')
            ->add('value', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\ValueForm',
                    'label' => false,
                ]
            ])
            ->add('narrative', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                    'label' => false,
                ],
            ]);
    }
}