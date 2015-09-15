<?php namespace App\Core\V201\Forms\Organization;

use Kris\LaravelFormBuilder\Form;

class MultipleDocumentLinkForm extends Form
{
    public function buildForm()
    {
        $this
            ->add(
                'documentLink',
                'collection',
                [
                    'type'      => 'form',
                    'prototype' => true,
                    'options'   => [
                        'class' => 'App\Core\V201\Forms\Organization\DocumentLinkForm',
                        'label' => false,
                    ],
                    'wrapper'   => false
                ]
            );
    }
}