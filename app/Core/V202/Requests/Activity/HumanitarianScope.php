<?php namespace App\Core\V202\Requests\Activity;

use App\Core\V201\Requests\Activity\ActivityBaseRequest;

/**
 * Class HumanitarianScope
 * @package App\Core\V202\Requests\Activity
 */
class HumanitarianScope extends ActivityBaseRequest
{
    public function rules()
    {
        return $this->getRulesForHumanitarianScope($this->get('humanitarian_scope'));
    }

    public function messages()
    {
        return $this->getMessagesForHumanitarianScope($this->get('humanitarian_scope'));
    }

    /**
     * returns rules for HumanitarianScope
     * @param $formFields
     * @return array|mixed
     */
    public function getRulesForHumanitarianScope($formFields)
    {
        $rules = [];

        foreach ($formFields as $humanitarianScopeIndex => $humanitarianScope) {
            $humanitarianScopeForm                             = 'humanitarian_scope.' . $humanitarianScopeIndex;
            $rules[$humanitarianScopeForm . '.type']           = 'required';
            $rules[$humanitarianScopeForm . '.vocabulary']     = 'required';
            $rules[$humanitarianScopeForm . '.vocabulary_uri'] = 'url';
            $rules[$humanitarianScopeForm . '.code']           = 'required|string';
            $rules                                             = array_merge($rules, $this->getRulesForNarrative($humanitarianScope['narrative'], $humanitarianScopeForm));
        }

        return $rules;
    }

    /**
     * returns messages for HumanitarianScope
     * @param $formFields
     * @return array|mixed
     */
    public function getMessagesForHumanitarianScope($formFields)
    {
        $messages = [];

        foreach ($formFields as $humanitarianScopeIndex => $humanitarianScope) {
            $humanitarianScopeForm                                     = 'humanitarian_scope.' . $humanitarianScopeIndex;
            $messages[$humanitarianScopeForm . '.type.required']       = 'Humanitarian Scope type is required';
            $messages[$humanitarianScopeForm . '.vocabulary.required'] = 'Humanitarian Scope vocabulary is required';
            $messages[$humanitarianScopeForm . '.code.required']       = 'Humanitarian Scope code is required';
            $messages[$humanitarianScopeForm . '.code.string']         = 'Humanitarian Scope should be string';
            $messages[$humanitarianScopeForm . '.vocabulary_uri.url']  = 'Enter valid URL. eg. http://example.com';
            $messages                                                  = array_merge($messages, $this->getMessagesForNarrative($humanitarianScope['narrative'], $humanitarianScopeForm));
        }

        return $messages;
    }
}
