<?php namespace App\Core\V201\Forms\Activity;

use App\Core\Form\BaseForm;

class DocumentLinks extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('document_link', 'Activity\DocumentLink', 'document_link')
            ->addAddMoreButton('add_document_link', 'document_link');
    }
}
