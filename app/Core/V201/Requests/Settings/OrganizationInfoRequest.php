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
    }

    public function rules()
    {
        $rules = [];

        $org_id               = session('org_id');
        $user_identifier_rule = sprintf('required|unique:organizations,user_identifier,%s', $org_id);

        $narratives                     = $this->get('narrative');
        $rules['narrative.*.narrative'] = 'required';
        $rules['narrative']             = 'unique_lang|unique_default_lang';
        $rules['country']               = 'required';
        $rules['organization_type']     = 'required';
        $rules['user_identifier']       = $user_identifier_rule;
        $rules['registration_agency']   = 'required';
        $rules['registration_number']   = 'required';
        $rules['logo']                  = 'image';
        $rules['organization_url']      = 'url';

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        $messages['narrative.unique_lang']          = 'Language should be unique';
        $messages['narrative.*.narrative.required'] = 'At least one organisation name is required';
        $messages['country.required']               = 'Country is required';
        $messages['user_identifier.required']       = 'User Identifier is required';
        $messages['user_identifier.unique']         = 'Sorry! this User Identifier is already taken';
        $messages['organization_type.required']     = 'Organisation Type is required';
        $messages['registration_number.required']   = 'Organisation Registration number is required';
        $messages['registration_agency.required']   = 'Organisation Registration Agency is required';
        $messages['logo.image']                     = 'Please select an image file';
        $messages['organization_url.url']           = 'Please enter valid organization url';

        return $messages;
    }
}