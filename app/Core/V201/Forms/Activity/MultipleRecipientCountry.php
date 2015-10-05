<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleRecipientCountry
 * @package App\Core\V201\Forms\Activity
 */
class MultipleRecipientCountry extends Form
{
    /**
     * builds recipient country form
     */
    public function buildForm()
    {
        $this
            ->add(
                'recipient_country',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\RecipientCountry',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form recipient_country'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'recipient_country'
                    ]
                ]
            );
    }
}
