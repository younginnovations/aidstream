<?php namespace App\Core\V201\Forms\Activity;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OwnerOrg
 * Contains the function that creates Owner Org Form
 * @package App\Core\V201\Forms\Activity
 */
class OwnerOrg extends Form
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
                        'class' => 'App\Core\V201\Forms\Activity\Narrative',
                        'label' => false,
                    ],
                    'wrapper'   => [
                        'class' => 'collection_form owner_organization_narrative'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'owner_organization_narrative'
                    ]
                ]
            );
    }
}
