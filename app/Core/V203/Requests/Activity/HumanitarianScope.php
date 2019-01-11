<?php namespace App\Core\V203\Requests\Activity;

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
            $messages[$humanitarianScopeForm . '.type.required']       = trans('validation.required', ['attribute' => trans('elementForm.humanitarian_scope_type')]);
            $messages[$humanitarianScopeForm . '.vocabulary.required'] = trans('validation.required', ['attribute' => trans('elementForm.humanitarian_scope_vocabulary')]);
            $messages[$humanitarianScopeForm . '.code.required']       = trans('validation.required', ['attribute' => trans('elementForm.humanitarian_scope_code')]);
            $messages[$humanitarianScopeForm . '.code.string']         = trans('validation.string', ['attribute' => trans('element.humanitarian_scope')]);
            $messages[$humanitarianScopeForm . '.vocabulary_uri.url']  = trans('validation.url');
            $messages                                                  = array_merge($messages, $this->getMessagesForNarrative($humanitarianScope['narrative'], $humanitarianScopeForm));
        }

        return $messages;
    }
}
