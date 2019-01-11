<?php namespace App\Core\V203\Forms\Activity;

use App\Core\Form\BaseForm;

class DocumentLinks extends BaseForm
{
    public function buildForm()
    {
        $this
            ->addCollection('document_link', 'Activity\DocumentLink', 'document_link');
    }
}
