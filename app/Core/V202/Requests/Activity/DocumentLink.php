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
            $documentLinkForm                                                        = sprintf('document_link.%s', $documentLinkIndex);
            $rules[sprintf('document_link.%s.url', $documentLinkIndex)][]              = 'required|url';
            $rules[sprintf('document_link.%s.format', $documentLinkIndex)]           = 'required';
            $rules                                                                   = array_merge(
                $rules,
                $this->getRulesForNarrative($documentLink['title'][0]['narrative'], sprintf('%s.title.0', $documentLinkForm)),
                $this->getRulesForDocumentCategory($documentLink['category'], $documentLinkForm)
            );
            $rules[sprintf('%s.title.0.narrative.0.narrative', $documentLinkForm)][] = 'required';
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
            $documentLinkForm                                                                  = sprintf('document_link.%s', $documentLinkIndex);
            $messages[sprintf('document_link.%s.url.required', $documentLinkIndex)]            = trans('validation.required', ['attribute' => trans('elementForm.url')]);
            $messages[sprintf('document_link.%s.url.url', $documentLinkIndex)]                 = trans('validation.url');
            $messages[sprintf('document_link.%s.format.required', $documentLinkIndex)]         = trans('validation.required', ['attribute' => trans('elementForm.format')]);
            $messages                                                                          = array_merge(
                $messages,
                $this->getMessagesForNarrative($documentLink['title'][0]['narrative'], sprintf('%s.title.0', $documentLinkForm)),
                $this->getMessagesForDocumentCategory($documentLink['category'], $documentLinkForm)
            );
            $messages[sprintf('%s.title.0.narrative.0.narrative.required', $documentLinkForm)] = trans('validation.required', ['attribute' => trans('elementForm.narrative')]);
        }

        return $messages;

    }
}
