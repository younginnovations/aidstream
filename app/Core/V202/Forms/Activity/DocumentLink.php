<?php namespace App\Core\V202\Forms\Activity;

use App\Core\V201\Forms\Activity\DocumentLink as V201DocumentLink;

class DocumentLink extends V201DocumentLink
{
    public function buildForm()
    {
        $this
            ->add('url', 'text')
            ->addSelect('format', $this->getCodeList('FileFormat', 'Activity'))
            ->addCollection('title', 'Activity\Title')
            ->addCollection('category', 'Activity\CategoryCode', 'category')
            ->addAddMoreButton('add_category', 'category')
            ->addCollection('language', 'Activity\LanguageCode', 'language')
            ->addAddMoreButton('add_language', 'language')
            ->addCollection('document_date', 'Activity\PeriodStart')
            ->addRemoveThisButton('remove_document_link');
    }
}
