<?php namespace App\Core\V202\Forms\Activity;

use App\Core\V201\Forms\Activity\DocumentLink as V201DocumentLink;

class DocumentLink extends V201DocumentLink
{
    public function buildForm()
    {
        $this
            ->add('url', 'text', ['attr' => ['class' => 'document_link'], 'help_block' => $this->addHelpText('Activity_DocumentLink-url'), 'required' => true])
            ->addSelect('format', $this->getCodeList('FileFormat', 'Activity'), 'Format', $this->addHelpText('Activity_DocumentLink-format'), null, true)
            ->add(
                'upload_text',
                'static',
                [
                    'tag'           => 'em',
                    'label'         => false,
                    'default_value' => 'If your document is not uploaded, <a href="#" data-toggle="modal" data-target="#upload_document" data-modal-type="upload">Upload it</a> in AidStream. You can also add from your <a href="#" data-toggle="modal" data-target="#upload_document">existing</a> documents in Aidstream'
                ]
            )
            ->addCollection('title', 'Activity\Title')
            ->addCollection('category', 'Activity\CategoryCode', 'category')
            ->addAddMoreButton('add_category', 'category')
            ->addCollection('language', 'Activity\LanguageCode', 'language')
            ->addAddMoreButton('add_language', 'language')
            ->addCollection('document_date', 'Activity\PeriodStart');
    }
}
