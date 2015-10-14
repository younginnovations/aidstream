<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class MultipleActivityDate
 * @package App\Core\V201\Forms\Activity
 */
class MultipleActivityDate extends BaseForm
{
    /**
     * builds activity date form
     */
    public function buildForm()
    {
        $this
            ->add(
                'activity_date',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\ActivityDate',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form Activity_date'
                    ]
                ]
            )
            ->addAddMoreButton('add_Activity_date', 'Activity_date');
    }
}
