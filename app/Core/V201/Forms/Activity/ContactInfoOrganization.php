<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class ContactInfoOrganization
 * @package App\Core\V201\Forms\Activity
 */
class ContactInfoOrganization extends Form
{
    /**
     * builds the contact info organization form
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
                        'data'  => ['narrativeLabel' => 'Organization Name']
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
