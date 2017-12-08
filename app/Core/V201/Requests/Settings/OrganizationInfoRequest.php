<?php namespace App\Core\V201\Requests\Settings;


use App\Helpers\GetCodeName;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationInfoRequest extends Request
{
    public function __construct()
    {
        Validator::extendImplicit(
            'unique_lang',
            function ($attribute, $value, $parameters, $validator) {
                $languages = [];
                foreach ($value as $narrative) {
                    $language = $narrative['language'];
                    if (in_array($language, $languages)) {
                        return false;
                    }
                    $languages[] = $language;
                }

                return true;
            }
        );

        Validator::extendImplicit(
            'unique_default_lang',
            function ($attribute, $value, $parameters, $validator) {
                $languages       = [];
                $defaultLanguage = getDefaultLanguage();

                $validator->addReplacer(
                    'unique_default_lang',
                    function ($message, $attribute, $rule, $parameters) use ($validator, $defaultLanguage) {
                        return str_replace(':language', app(GetCodeName::class)->getActivityCodeName('Language', $defaultLanguage), $message);
                    }
                );

                $check = true;
                foreach ($value as $narrative) {
                    $languages[] = $narrative['language'];
                }

                if (count($languages) === count(array_unique($languages))) {
                    if (in_array("", $languages) && in_array($defaultLanguage, $languages)) {
                        $check = false;
                    }
                }

                return $check;
            }
        );

        Validator::extend(
            'no_spaces',
            function ($attribute, $value, $parameters, $validator) {
                if (preg_match('/\s/', $value)) {
                    return false;
                }

                return true;
            }
        );
    }

    public function rules()
    {
        $rules = [];

        $org_id               = session('org_id');
        $user_identifier_rule = sprintf('required|no_spaces|unique:organizations,user_identifier,%s', $org_id);

        $rules['narrative.*.narrative'] = 'required';
        $rules['narrative']             = 'unique_lang|unique_default_lang';
        $rules['country']               = 'required';
        $rules['organization_type']     = 'required';
        $rules['user_identifier']       = $user_identifier_rule;
        $rules['registration_agency']   = 'required';
        $rules['registration_number']   = 'required|regex:/^[0-9a-zA-Z-_\/:]+$/';
        $rules['logo']                  = 'image';
        $rules['organization_url']      = 'url';

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $messages['narrative.unique_lang']          = trans('validation.unique', ['attribute' => trans('elementForm.language')]);
        $messages['narrative.*.narrative.required'] = trans('validation.org_required');
        $messages['country.required']               = trans('validation.required', ['attribute' => trans('elementForm.country')]);
        $messages['user_identifier.required']       = trans('validation.required', ['attribute' => trans('elementForm.user_identifier')]);
        $messages['user_identifier.unique']         = trans('validation.user_identifier_taken');
        $messages['user_identifier.no_spaces']      = trans('validation.spaces_not_allowed');
        $messages['organization_type.required']     = trans('validation.required', ['attribute' => trans('elementForm.organisation_type')]);
        $messages['registration_number.required']   = trans('validation.required', ['attribute' => trans('organisation.organisation_registration_number')]);
        $messages['registration_number.regex']      = trans('validation.regex', ['attribute' => '-' . ',' . '_']);
        $messages['registration_agency.required']   = trans('validation.required', ['attribute' => trans('organisation.organisation_registration_agency')]);
        $messages['logo.image']                     = trans('validation.image');
        $messages['organization_url.url']           = trans('validation.enter_valid', ['attribute' => trans('organisation.organisation_url')]);

        return $messages;
    }
}
