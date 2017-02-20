<?php namespace App\Lite\Forms\V202;


use App\Lite\Forms\LiteBaseForm;

/**
 * Class DocumentLinks
 * @package App\Lite\Forms\V202
 */
class DocumentLinks extends LiteBaseForm
{
    /**
     * Build the form of document link
     */
    public function buildForm()
    {
        $document_title_label = $this->getDocumentLinksTitleLabel();
        $document_url_label   = $this->getDocumentLinksUrlLabel();

        $this->addText('document_title', $document_title_label, false)
             ->addText('document_url', $document_url_label, false)
             ->add('document_link_id', 'hidden');
    }

    /**
     * Returns label for documentLink title label based on document category.
     * @return string
     */
    protected function getDocumentLinksTitleLabel()
    {
        if ($this->isOutcomesDocument()) {
            return trans('lite/elementForm.outcomes_document_title');
        }

        return trans('lite/elementForm.annual_report_title');
    }

    /**
     * Return label for documentLink url label based on document category.
     *
     * @return string
     */
    protected function getDocumentLinksUrlLabel()
    {
        if ($this->isOutcomesDocument()) {
            return trans('lite/elementForm.outcomes_document_url');
        }

        return trans('lite/elementForm.annual_report_url');
    }

    /**
     * Check if the form request if for outcomes document.
     *
     * @return bool
     */
    protected function isOutcomesDocument()
    {
        if (substr($this->name, 0, 17) === 'outcomes_document') {
            return true;
        }

        return false;
    }
}