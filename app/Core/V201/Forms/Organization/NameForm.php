<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class NameForm extends Form
{
    protected $showFieldErrors = true;

    public function buildForm()
    {
        $this
            ->add('name', 'collection', [
                'type' => 'form',
                'prototype' => true,
                'options' => [
                    'class' => 'App\Core\V201\Forms\Organization\NarrativeForm',
                    'label' => false,
                ],
                'wrapper' => false
            ]);
    }
}