<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleDocumentLinkForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->add(
                'documentLink',
                'collection',
                [
                    'type' => 'form',
                    'options' => [
                        'class' => 'App\Core\V201\Forms\Organization\DocumentLinkForm',
                        'label' => false,
                    ],
                    'wrapper' => [
                        'class' => 'collection_form document_link'
                    ]
                ]
            )
            ->addAddMoreButton('add_document_link', 'document_link');
    }
}
