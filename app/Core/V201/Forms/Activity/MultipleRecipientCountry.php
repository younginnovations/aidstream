<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleRecipientCountry
 * @package App\Core\V201\Forms\Activity
 */
class MultipleRecipientCountry extends BaseForm
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
            ->addAddMoreButton('add_recipient_country', 'recipient_country');
    }
}
