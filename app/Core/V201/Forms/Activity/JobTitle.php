<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class JobTitle
 * @package App\Core\V201\Forms\Activity
 */
class JobTitle extends Form
{
    /**
     * builds the contact info Job Title form
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
                        'class' => 'collection_form jobTitleNarrative'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'jobTitleNarrative'
                    ]
                ]
            );
    }
}
