<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

/**
 * Class Name
 * @package App\Core\V201\Forms\Activity
 */
class Name extends BaseForm
{
    protected $showFieldErrors = true;

    /**
     * builds name form
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
                        'class' => 'collection_form name_narrative'
                    ]
                ]
            )
            ->addAddMoreButton('add', 'name_narrative');
    }
}
