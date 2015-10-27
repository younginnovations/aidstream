<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class LocationDescription
 * @package App\Core\V201\Forms\Activity
 */
class LocationDescription extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds location description form
     */
    public function buildForm()
    {
        $this
            ->add(
                'narrative',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\Narrative',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form location_description_narrative'
                    ]
                ]
            )
            ->addAddMoreButton('add', 'location_description_narrative');
    }
}
