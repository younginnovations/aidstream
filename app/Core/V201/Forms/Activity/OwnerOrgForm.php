<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OwnerOrgForm
 * @package App\Core\V201\Forms\Activity
 */
class OwnerOrgForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('reference', 'text')
            ->add(
                'narrative',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Activity\NarrativeForm',
                        'label' => false,
                    ],
                ]
            );
    }
}
