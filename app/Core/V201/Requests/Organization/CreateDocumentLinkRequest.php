<?php namespace App\Core\V201\Requests\Organization;


/**
 * Class CreateDocumentLinkRequest
 * @package App\Core\V201\Requests\Organization
 */
class CreateDocumentLinkRequest extends OrganizationBaseRequest
{

    protected $redirect;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
    public function getRulesForDocumentLink(array $formFields)
    {
        $rules = [];
        foreach ($formFields as $documentLinkIndex => $documentLink) {
            $documentLinkForm                                              = sprintf(
                'document_link.%s',
                $documentLinkIndex
            );
            $rules[sprintf('document_link.%s.url', $documentLinkIndex)]    = 'required|url';
            $rules[sprintf('document_link.%s.format', $documentLinkIndex)] = 'required';
            $rules                                                         = array_merge(
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
            $documentLinkForm                                                          = sprintf(
                'document_link.%s',
                $documentLinkIndex
            );
            $messages[sprintf('document_link.%s.url.required', $documentLinkIndex)]    = trans('validation.required', ['attribute' => trans('elementForm.url')]);
            $messages[sprintf(
                'document_link.%s.url.url',
                $documentLinkIndex
            )]                                                                         = trans('validation.url');
            $messages[sprintf('document_link.%s.format.required', $documentLinkIndex)] = trans('validation.required', ['attribute' => trans('elementForm.format')]);
            $messages                                                                  = array_merge(
                $messages,
                $this->getMessagesForNarrative($documentLink['narrative'], $documentLinkForm),
                $this->getMessagesForDocumentCategory($documentLink['category'], $documentLinkForm),
                $this->getMessagesForRecipientCountry($documentLink['recipient_country'], $documentLinkForm)
            );
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    public function getRulesForDocumentCategory($formFields, $formIndex)
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
    public function getMessagesForDocumentCategory($formFields, $formIndex)
    {
        $messages = [];
        foreach ($formFields as $documentCategoryIndex => $documentCategory) {
            $messages[sprintf(
                '%s.category.%s.code.required',
                $formIndex,
                $documentCategoryIndex
            )] = trans('validation.required', ['attribute' => trans('elementForm.category')]);
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    public function getRulesForRecipientCountry($formFields, $formIndex)
    {
        $rules = [];
        foreach ($formFields as $recipientCountryIndex => $recipientCountryVal) {
            $budgetItemForm = sprintf('%s.recipient_country.%s', $formIndex, $recipientCountryIndex);
            $rules          = array_merge(
                $rules,
                $this->getRulesForNarrative($recipientCountryVal['narrative'], $budgetItemForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    public function getMessagesForRecipientCountry($formFields, $formIndex)
    {
        $messages = [];
        foreach ($formFields as $recipientCountryIndex => $recipientCountryVal) {
            $budgetItemForm = sprintf('%s.recipient_country.%s', $formIndex, $recipientCountryIndex);
            $messages       = array_merge(
                $messages,
                $this->getMessagesForNarrative($recipientCountryVal['narrative'], $budgetItemForm)
            );
        }

        return $messages;
    }
}
