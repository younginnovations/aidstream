<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Locations
 * @package App\Core\V201\Forms\Activity
 */
class Locations extends BaseForm
{
    /**
     * builds locations form
     */
    public function buildForm()
    {
        $this
            ->add(
                'location',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Location',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form location'
                    ]
                ]
            )
            ->addAddMoreButton('add', 'recipient_country');
    }
}
