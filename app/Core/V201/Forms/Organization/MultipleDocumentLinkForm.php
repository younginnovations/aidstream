<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

class MultipleDocumentLinkForm extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('documentLink', 'Organization\DocumentLinkForm', 'document_link')
            ->addAddMoreButton('add_document_link', 'document_link');
    }
}
