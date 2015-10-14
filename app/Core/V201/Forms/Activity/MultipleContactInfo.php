<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleContactInfo
 * @package App\Core\V201\Forms\Activity
 */
class MultipleContactInfo extends BaseForm
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
            ->addAddMoreButton('add_contactInfo', 'contactInfo');
    }
}
