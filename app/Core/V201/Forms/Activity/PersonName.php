<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class PersonName
 * @package App\Core\V201\Forms\Activity
 */
class PersonName extends Form
{
    /**
     * builds the contact info Person Name form
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
                        'class' => 'collection_form personNameNarrative'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'personNameNarrative'
                    ]
                ]
            );
    }
}
