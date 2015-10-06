<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleRecipientRegion
 * @package App\Core\V201\Forms\Activity
 */
class MultipleRecipientRegion extends Form
{
    /**
     * builds activity Recipient Region form
     */
    public function buildForm()
    {
        $this
            ->add(
                'recipient_region',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\RecipientRegion',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form recipient_region'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'recipient_region'
                    ]
                ]
            );
    }
}
