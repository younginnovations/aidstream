<?php namespace App\Core\V202\Requests\Organization;

use App\Core\V201\Requests\Organization\CreateDocumentLinkRequest as V201CreateDocumentLinkRequest;

/**
 * Class CreateDocumentLinkRequest
 * @package App\Core\V202\Requests\Organization
 */
class CreateDocumentLinkRequest extends V201CreateDocumentLinkRequest
{
    /**
     * @param array $formFields
     * @return array
     */
    public function getRulesForDocumentLink(array $formFields)
    {
        $rules = [];
        foreach ($formFields as $documentLinkIndex => $documentLink) {
            $documentLinkForm                                                            = sprintf(
                'document_link.%s',
                $documentLinkIndex
            );
            $rules[sprintf('document_link.%s.url', $documentLinkIndex)]                  = 'required|url';
            $rules[sprintf('document_link.%s.format', $documentLinkIndex)]               = 'required';
            $rules[sprintf('document_link.%s.document_date.0.date', $documentLinkIndex)] = 'date';
            $rules                                                                       = array_merge(
                $rules,
                $this->getRulesForNarrative($documentLink['narrative'], $documentLinkForm),
                $this->getRulesForDocumentCategory($documentLink['category'], $documentLinkForm),
                $this->getRulesForRecipientCountry($documentLink['recipient_country'], $documentLinkForm)
            );
        }

        return $rules;
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function getMessagesForDocumentLink(array $formFields)
    {
        $messages = [];
        foreach ($formFields as $documentLinkIndex => $documentLink) {
            $documentLinkForm                                                                        = sprintf(
                'document_link.%s',
                $documentLinkIndex
            );
            $messages[sprintf('document_link.%s.url.required', $documentLinkIndex)]                  = 'Url is required';
            $messages[sprintf(
                'document_link.%s.url.url',
                $documentLinkIndex
            )]                                                                                       = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('document_link.%s.format.required', $documentLinkIndex)]               = 'Format is required';
            $messages[sprintf('document_link.%s.document_date.0.date.date', $documentLinkIndex)]     = 'Please enter a valid date.';
            $messages                                                                                = array_merge(
                $messages,
                $this->getMessagesForNarrative($documentLink['narrative'], $documentLinkForm),
                $this->getMessagesForDocumentCategory($documentLink['category'], $documentLinkForm),
                $this->getMessagesForRecipientCountry($documentLink['recipient_country'], $documentLinkForm)
            );
        }

        return $messages;
    }
}
