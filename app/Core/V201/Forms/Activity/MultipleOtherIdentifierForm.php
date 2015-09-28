<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

class MultipleOtherIdentifierForm extends Form
{
    public function buildForm()
    {
        $this
            ->add(
                'otherIdentifier',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\OtherIdentifierForm',
                        'label' => false,
                    ],
                    'wrapper'   => false
                ]
            );
    }
}
