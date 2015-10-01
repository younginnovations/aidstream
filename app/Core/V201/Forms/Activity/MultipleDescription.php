<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleDescription
 * @package App\Core\V201\Forms\Activity
 */
class MultipleDescription extends Form
{
    /**
     * builds activity description form
     */
    public function buildForm()
    {
        $this
            ->add(
                'description',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Description',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form description'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'description'
                    ]
                ]
            );
    }
}
