<?php namespace App\Core\V201\Requests\Activity;


/**
 * Class DocumentLink
 * @package App\Core\V201\Requests\Activiry
 */
class DocumentLink extends ActivityBaseRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return $this->getRulesForDocumentLink($this->get('document_link'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->getMessagesForDocumentLink($this->get('document_link'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    protected function getRulesForDocumentLink(array $formFields)
    {
        $rules = [];
        foreach ($formFields as $documentLinkIndex => $documentLink) {
            $documentLinkForm                                                        = sprintf('document_link.%s', $documentLinkIndex);
            $rules[sprintf('document_link.%s.url', $documentLinkIndex)]              = 'required|url';
            $rules[sprintf('document_link.%s.format', $documentLinkIndex)]           = 'required';
            $rules                                                                   = array_merge(
                $rules,
                $this->getRulesForNarrative(getVal($documentLink, ['title', 0, 'narrative']), sprintf('%s.title.0', $documentLinkForm)),
                $this->getRulesForDocumentCategory(getVal($documentLink, ['category'], []), $documentLinkForm),
                $this->getRulesForDocumentLanguage(getVal($documentLink, ['language'], []), $documentLinkForm)
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
            $messages[sprintf('document_link.%s.url.required', $documentLinkIndex)]            = 'Url is required';
            $messages[sprintf('document_link.%s.url.url', $documentLinkIndex)]                 = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('document_link.%s.format.required', $documentLinkIndex)]         = 'Format is required';
            $messages                                                                          = array_merge(
                $messages,
                $this->getMessagesForNarrative(getVal($documentLink, ['title', 0, 'narrative']), sprintf('%s.title.0', $documentLinkForm)),
                $this->getMessagesForDocumentCategory(getVal($documentLink, ['category'], []), $documentLinkForm),
                $this->getMessagesForDocumentLanguage(getVal($documentLink, ['language'], []), $documentLinkForm)
            );
            $messages[sprintf('%s.title.0.narrative.0.narrative.required', $documentLinkForm)] = 'Narrative is required.';
        }

        return $messages;

    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    protected function getRulesForDocumentCategory($formFields, $formIndex)
    {
        $rules = [];
        foreach ($formFields as $documentCategoryIndex => $documentCategory) {
            $rules[sprintf('%s.category.%s.code', $formIndex, $documentCategoryIndex)] = 'required';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    protected function getMessagesForDocumentCategory($formFields, $formIndex)
    {
        $messages = [];
        foreach ($formFields as $documentCategoryIndex => $documentCategory) {
            $messages[sprintf('%s.category.%s.code.required', $formIndex, $documentCategoryIndex)] = 'Category is required';
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    protected function getRulesForDocumentLanguage($formFields, $formIndex)
    {
        $rules = [];
        foreach ($formFields as $languageKey => $languageVal) {
            $rules[sprintf('%s.language.%s.language', $formIndex, $languageKey)] = 'required';
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    protected function getMessagesForDocumentLanguage($formFields, $formIndex)
    {
        $messages = [];
        foreach ($formFields as $languageKey => $languageVal) {
            $messages[sprintf('%s.language.%s.language.required', $formIndex, $languageKey)] = 'Language is required';
        }

        return $messages;
    }
}
