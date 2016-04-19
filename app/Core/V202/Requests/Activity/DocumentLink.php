<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\DocumentLink as V201DocumentLink;

/**
 * Class DocumentLink
 * @package App\Core\V202\Requests\Activiry
 */
class DocumentLink extends V201DocumentLink
{
    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForDocumentLink(array $formFields)
    {
        $rules = [];
        foreach ($formFields as $documentLinkIndex => $documentLink) {
            $documentLinkForm                                              = sprintf('document_link.%s', $documentLinkIndex);
            $rules[sprintf('document_link.%s.url', $documentLinkIndex)]    = 'required|url';
            $rules[sprintf('document_link.%s.format', $documentLinkIndex)] = 'required';
            $rules                                                         = array_merge(
                $rules,
                $this->getRulesForNarrative(getVal($documentLink, ['title', 0, 'narrative']), sprintf('%s.title.0', $documentLinkForm)),
                $this->getRulesForDocumentCategory(getVal($documentLink, ['category'], []), $documentLinkForm),
                $this->getRulesForDocumentLanguage(getVal($documentLink, ['language'], []), $documentLinkForm),
                $this->getRulesForDocumentDate(getVal($documentLink, ['document_date'], []), $documentLinkForm)
            );
        }

        return $rules;

    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getMessagesForDocumentLink(array $formFields)
    {
        $messages = [];
        foreach ($formFields as $documentLinkIndex => $documentLink) {
            $documentLinkForm                                                          = sprintf('document_link.%s', $documentLinkIndex);
            $messages[sprintf('document_link.%s.url.required', $documentLinkIndex)]    = 'Url is required';
            $messages[sprintf('document_link.%s.url.url', $documentLinkIndex)]         = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('document_link.%s.format.required', $documentLinkIndex)] = 'Format is required';
            $messages                                                                  = array_merge(
                $messages,
                $this->getMessagesForNarrative(getVal($documentLink, ['title', 0, 'narrative']), sprintf('%s.title.0', $documentLinkForm)),
                $this->getMessagesForDocumentCategory(getVal($documentLink, ['category'], []), $documentLinkForm),
                $this->getMessagesForDocumentLanguage(getVal($documentLink, ['language'], []), $documentLinkForm),
                $this->getMessagesForDocumentDate(getVal($documentLink, ['document_date'], []), $documentLinkForm)
            );
        }

        return $messages;

    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    protected function getRulesForDocumentDate($formFields, $formIndex)
    {
        $rules = [];
        foreach ($formFields as $dateKey => $dateVal) {
            $rules[sprintf('%s.document_date.%s.date', $formIndex, $dateKey)] = 'required|date';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    protected function getMessagesForDocumentDate($formFields, $formIndex)
    {
        $messages = [];
        foreach ($formFields as $dateKey => $dateVal) {
            $messages[sprintf('%s.document_date.%s.date.required', $formIndex, $dateKey)] = 'Document date is required.';
            $messages[sprintf('%s.document_date.%s.date.date', $formIndex, $dateKey)]     = 'Please enter valid date.';
        }

        return $messages;
    }
}
