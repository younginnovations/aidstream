<?php namespace App\Core\V201\Forms\Organization;

use App\Core\Form\BaseForm;

/**
 * Class DocumentLinkForm
 * @package App\Core\V201\Forms\Organization
 */
class DocumentLinkForm extends BaseForm
{
    /**
     * build organization document link form
     */
    public function buildForm()
    {
        $this
            ->add('url', 'text', ['help_block' => $this->addHelpText('Organisation_DocumentLink-url'), 'required' => true])
            ->addSelect('format', $this->getCodeList('FileFormat', 'Organization'), trans('elementForm.format'), $this->addHelpText('Organisation_DocumentLink-format'), null, true, ['attr' => ['class' => 'document_link_format form-control']])
            ->addCollection('title', 'Activity\Title', '', ['narrative_true' => true], trans('elementForm.title'))
            ->addCollection('category', 'Organization\CategoryCodeForm', 'category', [], trans('elementForm.category'))
            ->addAddMoreButton('add_category', 'category')
            ->addCollection('language', 'Organization\LanguageCodeForm', 'language', [], trans('elementForm.language'))
            ->addAddMoreButton('add_language', 'language')
            ->addCollection('recipient_country', 'Organization\RecipientCountryForm', 'recipient_country', [], trans('elementForm.recipient_country'))
            ->addAddMoreButton('add_recipient_country', 'recipient_country')
            ->addRemoveThisButton('remove_document_link');
    }
}
