<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleActivityDate
 * @package App\Core\V201\Forms\Activity
 */
class MultipleActivityDate extends Form
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
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'Activity_date'
                    ]
                ]
            );
    }
}
