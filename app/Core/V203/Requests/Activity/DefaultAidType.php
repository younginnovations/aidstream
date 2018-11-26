<?php namespace App\Core\V203\Requests\Activity;

use App\Http\Requests\Request;

/**
 * Class DefaultAidType
 * @package App\Core\V201\Requests\Activity
 */
class DefaultAidType extends Request
{

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
     * get the validation rules that apply to the activity request.
     * @return array
     */
    public function rules()
    {
        return $this->getRules($this->get('default_aid_type'));
    }

    public function messages()
    {
        // $messages['default_aid_type.required'] = trans('validation.required', ['attribute' => trans('element.default_aid_type')]);

        // return $messages;
        return $this->getMessages($this->get('default_aid_type'));
    }

    /**
     * returns rules for Default Aid Type
     * @param $formFields
     * @return array|mixed
     */
    public function getRules($formFields)
    {
        $rules = [];
        foreach ($formFields as $aidtypeIndex => $aidtype) {
            $aidtypeForm                                                    = sprintf('default_aid_type.%s', $aidtypeIndex);
            // dd($aidtypeForm);
            $rules[sprintf('%s.default_aidtype_vocabulary', $aidtypeForm)]  = 'required';
 
            if ($aidtype['default_aidtype_vocabulary'] == 1) {
                $rules[sprintf('%s.default_aid_type', $aidtypeForm)] = 'required_with:' . $aidtypeForm . '.default_aidtype_vocabulary';
            } else if($aidtype['default_aidtype_vocabulary'] == 2) {
                $rules[sprintf('%s.earmarking_category', $aidtypeForm)] = 'required_with:' . $aidtypeForm . '.default_aidtype_vocabulary';
            } else if($aidtype['default_aidtype_vocabulary'] == 3) {
                $rules[sprintf('%s.default_aid_type_text', $aidtypeForm)] = 'required_with:' . $aidtypeForm. '.default_aidtype_vocabulary';
            }
        }

        return $rules;
    }

    /**
     * returns messages for Default Aid Type
     * @param $formFields
     * @return array|mixed
     */
    public function getMessages($formFields)
    {
        $messages = [];
        foreach ($formFields as $aidtypeIndex => $aidtype) {
            $aidtypeForm                                                    = sprintf('default_aid_type.%s', $aidtypeIndex);
            $messages[sprintf('%s.default_aidtype_vocabulary.required', $aidtypeForm)] = trans('validation.required', ['attribute' => trans('elementForm.default_aid_type')]);
 
            if ($aidtype['default_aidtype_vocabulary'] == 1) {
                $messages[sprintf('%s.default_aid_type.%s', $aidtypeForm, 'required_with')] = trans(
                    'validation.required_with',
                    ['attribute' => trans('elementForm.default_aid_type_code'), 'values' => trans('elementForm.default_aid_type_vocabulary')]
                );
            } else if($aidtype['default_aidtype_vocabulary'] == 2) {
                $messages[sprintf('%s.default_aid_type.%s', $aidtypeForm, 'required_with')] = trans(
                    'validation.required_with',
                    ['attribute' => trans('elementForm.default_aid_type_earmarking_category_code'), 'values' => trans('elementForm.default_aid_type_vocabulary')]
                );
            } else if($aidtype['default_aidtype_vocabulary'] == 3) {
                $messages[sprintf('%s.default_aid_type.%s', $aidtypeForm, 'required_with')] = trans(
                    'validation.required_with',
                    ['attribute' => trans('elementForm.default_aid_type_text'), 'values' => trans('elementForm.default_aid_type_vocabulary')]
                );
            }
        }

        return $messages;
    }
}
