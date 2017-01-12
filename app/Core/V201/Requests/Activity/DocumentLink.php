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
                $this->getRulesForDocumentCategory(getVal($documentLink, ['category'], []), $documentLinkForm)
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
                $this->getMessagesForNarrative(getVal($documentLink, ['title', 0, 'narrative']), sprintf('%s.title.0', $documentLinkForm)),
                $this->getMessagesForDocumentCategory(getVal($documentLink, ['category'], []), $documentLinkForm)
            );
            $messages[sprintf('%s.title.0.narrative.0.narrative.required', $documentLinkForm)] = trans('validation.required', ['attribute' => trans('elementForm.narrative')]);
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
            $messages[sprintf('%s.category.%s.code.required', $formIndex, $documentCategoryIndex)] = trans('validation.required', ['attribute' => trans('elementForm.category')]);
        }

        return $messages;
    }
}
