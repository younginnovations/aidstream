<?php namespace App\Core\V201\Requests\Organization;

use App\Http\Requests\Request;
use App\Models\OrganizationData;
use Illuminate\Foundation\Http\FormRequest;

class CreateDocumentLinkRequest extends Request {

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        foreach ($this->request->get('documentLink') as $key => $val) {
            $rules['documentLink.' . $key . '.url'] = 'required';
            $rules['documentLink.' . $key . '.format'] = 'required';
            foreach ($val['title'] as $titleKey => $titleVal) {
                $rules['documentLink.' . $key . '.title.' . $titleKey . '.narrative'] = 'required';
            }
            foreach ($val['category'] as $categoryKey => $categoryVal) {
                $rules['documentLink.' . $key . '.category.' . $categoryKey . '.category'] = 'required';
            }
            foreach ($val['language'] as $languageKey => $languageVal) {
                $rules['documentLink.' . $key . '.language.' . $languageKey . '.language'] = 'required';
            }
            foreach ($val['recipientCountry'] as $recipientCountryKey => $recipientCountryVal) {
                $rules['documentLink.' . $key . '.recipientCountry.' . $recipientCountryKey . '.code'] = 'required';
                foreach ($recipientCountryVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $rules['documentLink.' . $key . '.recipientCountry.' . $recipientCountryKey . '.narrative.' . $narrativeKey . '.narrative'] = 'required';
                }
            }
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        foreach ($this->request->get('documentLink') as $key => $val) {
            $messages['documentLink.' . $key . '.url'. '.required'] = sprintf("Url is Required.", $key);
            $messages['documentLink.' . $key . '.format' . '.required'] = sprintf("Document Link Format is Required.", $key);
            foreach ($val['title'] as $titleKey => $titleVal) {
                $messages['documentLink.' . $key . '.title.' . $titleKey . '.narrative' . '.required'] = sprintf("Title is Required.", $key);
            }
            foreach ($val['category'] as $categoryKey => $categoryVal) {
                $messages['documentLink.' . $key . '.category.' . $categoryKey . '.category' . '.required'] = sprintf("Category is Required.", $key);
            }
            foreach ($val['language'] as $languageKey => $languageVal) {
                $messages['documentLink.' . $key . '.language.' . $languageKey . '.language' . '.required'] = sprintf("Language is Required.", $key);
            }
            foreach ($val['recipientCountry'] as $recipientCountryKey => $recipientCountryVal) {
                $messages['documentLink.' . $key . '.recipientCountry.' . $recipientCountryKey . '.code' . '.required'] = sprintf("Code is Required.", $key);
                foreach ($recipientCountryVal['narrative'] as $narrativeKey => $narrativeVal) {
                    $messages['documentLink.' . $key . '.recipientCountry.' . $recipientCountryKey . '.narrative.' . $narrativeKey . '.narrative' . '.required'] = sprintf("Narrative is Required.", $key);
                }
            }
        }
        return $messages;
    }

}
