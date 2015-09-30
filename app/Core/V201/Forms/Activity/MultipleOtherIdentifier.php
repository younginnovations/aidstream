<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleOtherIdentifier
 * @package App\Core\V201\Forms\Activity
 */
class MultipleOtherIdentifier extends Form
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
                        'class' => 'App\Core\V201\Forms\Activity\OtherIdentifier',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form other_identifier'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'other_identifier'
                    ]
                ]
            );
    }
}
