<?php namespace App\Core\V201\Requests;

use App\Http\Requests\Request;
use App\Services\RegistrationAgencies;
use Illuminate\Support\Facades\Validator;
use App\Core\V201\Traits\GetCodes;

/**
 * Class RegisterOrganization
 * @package App\Core\V201\Requests
 */
class RegisterOrganization extends Request
{
    use GetCodes;

    /**
     * RegisterOrganization constructor.
     * @param RegistrationAgencies $regAgencies
     */
    public function __construct(RegistrationAgencies $regAgencies)
    {
        Validator::extend(
            'code_list',
            function ($attribute, $value, $parameters, $validator) {
                $listName = $parameters[1];
                $listType = $parameters[0];
                $codeList = $this->getCodes($listName, $listType);

                return in_array($value, $codeList);
            }
        );

        Validator::extend(
            'unique_org_identifier',
            function ($attribute, $value, $parameters, $validator) {
                $table    = 'organizations';
                $column   = 'reporting_org';
                $jsonPath = '{0,reporting_organization_identifier}';
                $builder  = \DB::table($table)->whereRaw(sprintf("%s #>> '{%s}' = ?", $column, str_replace('.', ',', $jsonPath)), [$value]);
                $count    = $builder->count();

                return $count === 0;
            }
        );
    }

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
        $regAgencies = implode(',', array_keys(json_decode($this->get('agencies'), true)));
        $rules       = [];

        $rules['organization_name']                = 'required';
        $rules['organization_name_abbr']           = 'required|unique:organizations,user_identifier';
        $rules['organization_type']                = 'required|code_list:Organization,OrganizationType';
        $rules['organization_address']             = 'required';
        $rules['country']                          = 'required|code_list:Organization,Country';
        $rules['organization_registration_agency'] = 'required|in:' . $regAgencies;
        $rules['registration_number']              = 'required|alpha_num';
        $rules['organization_identifier']          = 'required|unique_org_identifier';

        return $rules;
    }

    /**
     * Get the Validation Error message
     * @return array
     */
    public function messages()
    {
        $messages = [];

        $messages['organization_name.required']                    = trans('validation.required', ['attribute' => trans('organisation.organisation_name')]);
        $messages['organization_name_abbr.required']               = trans('validation.required', ['attribute' => trans('organisation.organisation_name_abbreviation')]);
        $messages['organization_name_abbr.unique']                 = trans('validation.custom_unique', ['attribute' => trans('organisation.organisation_name_abbreviation')]);
        $messages['organization_type.required']                    = trans('validation.required', ['attribute' => trans('elementForm.organisation_type')]);
        $messages['organization_type.code_list']                   = trans('validation.code_list', ['attribute' => trans('elementForm.organisation_type')]);
        $messages['organization_address.required']                 = trans('validation.required', ['attribute' => trans('elementForm.address')]);
        $messages['country.required']                              = trans('validation.required', ['attribute' => trans('elementForm.country')]);
        $messages['country.code_list']                             = trans('validation.code_list', ['attribute' => trans('elementForm.country')]);
        $messages['organization_registration_agency.required']     = trans('validation.required', ['attribute' => trans('organisation.organisation_registration_agency')]);
        $messages['organization_registration_agency.reg_agency']   = trans('validation.code_list', ['attribute' => trans('organisation.organisation_registration_agency')]);
        $messages['registration_number.required']                  = trans('validation.required', ['attribute' => trans('organisation.registration_number')]);
        $messages['registration_number.alpha_num']                 = trans('validation.alpha_num', ['attribute' => trans('organisation.registration_number')]);
        $messages['organization_identifier.required']              = trans('validation.required', ['attribute' => trans('organisation.organisational_iati_identifier')]);
        $messages['organization_identifier.unique_org_identifier'] = trans('validation.custom_unique', ['attribute' => trans('organisation.organisational_iati_identifier')]);

        return $messages;
    }

}
