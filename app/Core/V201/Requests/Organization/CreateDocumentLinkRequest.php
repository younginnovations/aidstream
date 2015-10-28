<?php namespace App\Core\V201\Requests\Organization;

use App\Models\OrganizationData;
use Illuminate\Foundation\Http\FormRequest;

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
        return $this->addRulesForDocumentLink($this->request->get('document_link'));
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->addMessagesForDocumentLink($this->request->get('document_link'));
    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addRulesForDocumentLink(array $formFields)
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
                $this->addRulesForNarrative($documentLink['narrative'], $documentLinkForm),
                $this->addRulesForDocumentCategory($documentLink['category'], $documentLinkForm),
                $this->addRulesForDocumentLanguage($documentLink['language'], $documentLinkForm),
                $this->addRulesForRecipientCountry($documentLink['recipient_country'], $documentLinkForm)
            );
        }

        return $rules;

    }

    /**
     * @param array $formFields
     * @return array
     */
    public function addMessagesForDocumentLink(array $formFields)
    {
        $messages = [];
        foreach ($formFields as $documentLinkIndex => $documentLink) {
            $documentLinkForm                                                          = sprintf(
                'document_link.%s',
                $documentLinkIndex
            );
            $messages[sprintf('document_link.%s.url.required', $documentLinkIndex)]    = 'Url is required';
            $messages[sprintf(
                'document_link.%s.url.url',
                $documentLinkIndex
            )]                                                                         = 'Enter valid URL. eg. http://example.com';
            $messages[sprintf('document_link.%s.format.required', $documentLinkIndex)] = 'Format is required';
            $messages                                                                  = array_merge(
                $messages,
                $this->addMessagesForNarrative($documentLink['narrative'], $documentLinkForm),
                $this->addMessagesForDocumentCategory($documentLink['category'], $documentLinkForm),
                $this->addMessagesForDocumentLanguage($documentLink['language'], $documentLinkForm),
                $this->addMessagesForRecipientCountry($documentLink['recipient_country'], $documentLinkForm)
            );
        }

        return $messages;

    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    public function addRulesForDocumentCategory($formFields, $formIndex)
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
    public function addMessagesForDocumentCategory($formFields, $formIndex)
    {
        $messages = [];
        foreach ($formFields as $documentCategoryIndex => $documentCategory) {
            $messages[sprintf(
                '%s.category.%s.code.required',
                $formIndex,
                $documentCategoryIndex
            )] = 'Category is required';
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    public function addRulesForDocumentLanguage($formFields, $formIndex)
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
    public function addMessagesForDocumentLanguage($formFields, $formIndex)
    {
        $messages = [];
        foreach ($formFields as $languageKey => $languageVal) {
            $messages[sprintf('%s.language.%s.language.required', $formIndex, $languageKey)] = 'Language is required';
        }

        return $messages;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    public function addRulesForRecipientCountry($formFields, $formIndex)
    {
        $rules = [];
        foreach ($formFields as $recipientCountryIndex => $recipientCountryVal) {
            $budgetItemForm                                                                     = sprintf(
                '%s.recipient_country.%s',
                $formIndex,
                $recipientCountryIndex
            );
            $rules[sprintf('%s.recipient_country.%s.code', $formIndex, $recipientCountryIndex)] = 'required';
            $rules                                                                              = array_merge(
                $rules,
                $this->addRulesForNarrative($recipientCountryVal['narrative'], $budgetItemForm)
            );
        }

        return $rules;
    }

    /**
     * @param $formFields
     * @param $formIndex
     * @return array
     */
    public function addMessagesForRecipientCountry($formFields, $formIndex)
    {
        $messages = [];
        foreach ($formFields as $recipientCountryIndex => $recipientCountryVal) {
            $budgetItemForm = sprintf(
                '%s.recipient_country.%s',
                $formIndex,
                $recipientCountryIndex
            );
            $messages[sprintf(
                '%s.recipient_country.%s.code.required',
                $formIndex,
                $recipientCountryIndex
            )]              = 'Code is required';
            $messages       = array_merge(
                $messages,
                $this->addMessagesForNarrative($recipientCountryVal['narrative'], $budgetItemForm)
            );
        }

        return $messages;
    }
}
