<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class ActivityDescription
 * @package App\Core\V201\Forms\Activity
 */
class ActivityDescription extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds activity description form
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
                        'class' => 'collection_form activity_description_narrative'
                    ]
                ]
            )
            ->addAddMoreButton('add', 'activity_description_narrative');
    }
}
