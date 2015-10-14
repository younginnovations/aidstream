<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleOtherIdentifier
 * @package App\Core\V201\Forms\Activity
 */
class MultipleOtherIdentifier extends BaseForm
{
    /**
     * builds multiple activity description form
     */
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
            ->addAddMoreButton('add_other_identifier', 'other_identifier');
    }
}
