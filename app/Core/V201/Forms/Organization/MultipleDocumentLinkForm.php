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
                    'type'    => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\DocumentLinkForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form document_link'
                    ]
                ]
            )
            ->add(
                'Add More',
                'button',
                [
                    'attr' => [
                        'class'           => 'add_to_collection',
                        'data-collection' => 'document_link'
                    ]
                ]
            );
    }
}