<?php namespace App\Core\V201\Forms\Organization;

use App\Core\ProtoName;
use Kris\LaravelFormBuilder\Form;

class BudgetLineForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('reference', 'text')
            ->add('value', 'collection', [
                'type' => 'form',
                'prototype_name' => '__NAME2__',
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

        new ProtoName($this);

    }
}