<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleRecipientRegion
 * @package App\Core\V201\Forms\Activity
 */
class MultipleRecipientRegion extends BaseForm
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
            ->addAddMoreButton('add_recipient_region', 'recipient_region');
    }
}
