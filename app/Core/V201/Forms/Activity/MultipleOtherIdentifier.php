<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class MultipleOtherIdentifier
 * @package App\Core\V201\Forms\Activity
 */
class MultipleOtherIdentifier extends Form
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
                    'wrapper'   => false
                ]
            );
    }
}
