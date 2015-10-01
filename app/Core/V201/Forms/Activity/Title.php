<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class Title
 * Contains the function to create the title form
 * @package App\Core\V201\Forms\Activity
 */
class Title extends Form
{
    /**
     * builds the activity title form
     */
    public function buildForm()
    {
        $this
            ->add(
                'title',
                'static',
                [
                    'default_value' => 'Title',
                    'label'         => false,
                    'wrapper'       => false
                ]
            )
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
                        'class' => 'collection_form title'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'title'
                    ]
                ]
            );
    }
}
