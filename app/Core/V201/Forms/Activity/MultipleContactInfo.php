<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleContactInfo
 * @package App\Core\V201\Forms\Activity
 */
class MultipleContactInfo extends Form
{
    /**
     * builds activity Contact Info
     */
    public function buildForm()
    {
        $this
            ->add(
                'contact_info',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\ContactInfo',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form contactInfo'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'contactInfo'
                    ]
                ]
            );
    }
}
