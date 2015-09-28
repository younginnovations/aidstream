<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleOtherIdentifierForm
 * @package App\Core\V201\Forms\Activity
 */
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
