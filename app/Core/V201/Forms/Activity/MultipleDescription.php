<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleDescription
 * @package App\Core\V201\Forms\Activity
 */
class MultipleDescription extends BaseForm
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
            ->addAddMoreButton('add_description', 'description');
    }
}
